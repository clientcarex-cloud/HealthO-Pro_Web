<?php
/**
 * Live legal metadata from the HealthO Pro master (modules/ccx_legal).
 *
 * The legal TEXT stays in each page file. The version, "Last updated", effective
 * date, version history and the current SaaS Agreement release come from the
 * CCX Legal module, so the site always shows what clients are asked to accept.
 *
 *   legal_meta_inline($slug, $fallback)  "Version 1.1 · Last updated: 17 November 2026 · Effective: …"
 *   legal_version_history($slug)         "Version history" table (below the document)
 *   legal_live_agreement()               current SaaS Agreement release (array|null)
 *
 * Fetched server-side (keyless public endpoint) and cached in the system temp dir.
 * If the master is unreachable, the stale cache is used; with no cache at all the
 * page shows its own hard-coded fallback, so a page never breaks.
 *
 * .env (optional):
 *   CCX_LEGAL_API_URL   default https://healtho.pro/ccx_legal/ccx_legal_api
 *   CCX_LEGAL_CACHE_TTL seconds, default 300
 *
 * Elements carrying the class "legal-live" are ignored when the master captures a
 * page's text fingerprint, so live metadata never changes the document hash.
 */

if (function_exists('legal_live_all')) {
    return;
}

function legal_live_env($key, $default = '')
{
    static $env = null;
    if ($env === null) {
        $env  = [];
        $path = dirname(__DIR__) . '/.env';
        if (is_readable($path)) {
            foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                $line = trim($line);
                if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
                    continue;
                }
                [$k, $v] = array_map('trim', explode('=', $line, 2));
                $env[$k] = trim($v, "\"'");
            }
        }
    }

    return isset($env[$key]) && $env[$key] !== '' ? $env[$key] : $default;
}

/** Whole payload: ['documents' => [slug => …], 'agreement' => …] or null. */
function legal_live_all()
{
    static $data = false;
    if ($data !== false) {
        return $data;
    }

    $api   = rtrim(legal_live_env('CCX_LEGAL_API_URL', 'https://healtho.pro/ccx_legal/ccx_legal_api'), '/');
    $ttl   = max(30, (int) legal_live_env('CCX_LEGAL_CACHE_TTL', '300'));
    $cache = rtrim(sys_get_temp_dir(), '/') . '/healtho_legal_' . md5($api) . '.json';

    $cached = is_readable($cache) ? json_decode((string) @file_get_contents($cache), true) : null;
    if (is_array($cached) && (time() - (int) @filemtime($cache)) < $ttl) {
        return $data = (!empty($cached['ok']) ? $cached : null);
    }
    if (is_array($cached) && empty($cached['ok'])) {
        $cached = null; // expired negative entry: try the master again
    }

    $fresh = null;
    $url   = $api . '/documents';
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 4,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER     => ['Accept: application/json'],
        ]);
        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if (is_string($body) && $code === 200) {
            $fresh = json_decode($body, true);
        }
    } else {
        $ctx  = stream_context_create(['http' => ['timeout' => 4, 'ignore_errors' => true]]);
        $body = @file_get_contents($url, false, $ctx);
        if ($body !== false) {
            $fresh = json_decode($body, true);
        }
    }

    if (is_array($fresh) && !empty($fresh['ok']) && isset($fresh['documents'])) {
        @file_put_contents($cache, json_encode($fresh), LOCK_EX);

        return $data = $fresh;
    }

    if (is_array($cached)) {
        @touch($cache, time() - $ttl + 60); // serve stale, retry the master in a minute
        return $data = $cached;
    }

    // Nothing cached and the master is unreachable: remember that for a minute so
    // visitors don't each wait for the timeout.
    @file_put_contents($cache, '{"ok":false}', LOCK_EX);
    @touch($cache, time() - $ttl + 60);

    return $data = null;
}

function legal_live_doc($slug)
{
    $all = legal_live_all();

    return $all['documents'][$slug] ?? null;
}

function legal_live_agreement()
{
    $all = legal_live_all();

    return $all['agreement'] ?? null;
}

function legal_live_date($ymd)
{
    $ts = $ymd ? strtotime($ymd) : false;

    return $ts ? date('j F Y', $ts) : '';
}

/** Version · Last updated · Effective, or "Last updated: $fallback" when offline. */
function legal_meta_inline($slug, $fallback)
{
    $d = legal_live_doc($slug);
    if (!$d || empty($d['last_updated'])) {
        echo 'Last updated: ' . htmlspecialchars($fallback);

        return;
    }

    $out = '<span class="legal-live">Version ' . htmlspecialchars((string) $d['version'])
        . ' · Last updated: <time datetime="' . htmlspecialchars($d['last_updated']) . '">' . htmlspecialchars(legal_live_date($d['last_updated'])) . '</time>';
    if (!empty($d['effective_date']) && $d['effective_date'] !== $d['last_updated']) {
        $out .= ' · Effective: <time datetime="' . htmlspecialchars($d['effective_date']) . '">' . htmlspecialchars(legal_live_date($d['effective_date'])) . '</time>';
    }
    echo $out . '</span>';
}

/** Version history + link to the SaaS Agreement. Renders nothing when offline. */
function legal_version_history($slug)
{
    $d = legal_live_doc($slug);
    if (!$d || empty($d['history'])) {
        return;
    }
    $a = legal_live_agreement();
    ?>
      <div class="legal-live legal-history">
        <h2 id="version-history" class="legal-related-h"><span class="legal-n">v</span>Version history</h2>
        <div class="legal-history-wrap">
          <table class="legal-history-table">
            <thead><tr><th>Version</th><th>Last updated</th><th>Effective</th><th>What changed</th></tr></thead>
            <tbody>
            <?php foreach ($d['history'] as $i => $v): ?>
              <tr<?= $i === 0 ? ' class="is-current"' : '' ?>>
                <td><b><?= htmlspecialchars((string) $v['version']) ?></b><?= $i === 0 ? ' <small>Current</small>' : '' ?></td>
                <td><?= htmlspecialchars(legal_live_date($v['last_updated'])) ?></td>
                <td><?= htmlspecialchars(legal_live_date($v['effective_date'])) ?></td>
                <td><?= nl2br(htmlspecialchars((string) $v['change_summary'])) ?></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php if ($a): ?>
          <p class="legal-history-note">This document is part of the <a href="/saas-agreement"><?= htmlspecialchars($a['title']) ?> (release <?= htmlspecialchars($a['version']) ?>)</a>, which every HealthO Pro customer accepts as a whole.</p>
        <?php endif; ?>
      </div>
    <?php
}

/* ───────────── Software Update & Maintenance notices (healtho.pro/maintenance) ───────────── */

/** Public notices feed, cached 60 s (maintenance status changes quickly). Null when unavailable. */
function legal_live_notices()
{
    static $data = false;
    if ($data !== false) {
        return $data;
    }

    $api   = rtrim(legal_live_env('CCX_LEGAL_API_URL', 'https://healtho.pro/ccx_legal/ccx_legal_api'), '/');
    $cache = rtrim(sys_get_temp_dir(), '/') . '/healtho_notices_' . md5($api) . '.json';
    $ttl   = 60;

    $cached = is_readable($cache) ? json_decode((string) @file_get_contents($cache), true) : null;
    if (is_array($cached) && (time() - (int) @filemtime($cache)) < $ttl) {
        return $data = (!empty($cached['ok']) ? $cached : null);
    }

    $fresh = null;
    if (function_exists('curl_init')) {
        $ch = curl_init($api . '/notices');
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 4, CURLOPT_CONNECTTIMEOUT => 3, CURLOPT_HTTPHEADER => ['Accept: application/json']]);
        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if (is_string($body) && $code === 200) {
            $fresh = json_decode($body, true);
        }
    }

    if (is_array($fresh) && !empty($fresh['ok'])) {
        @file_put_contents($cache, json_encode($fresh), LOCK_EX);
        return $data = $fresh;
    }
    if (is_array($cached) && !empty($cached['ok'])) {
        @touch($cache, time() - $ttl + 30);
        return $data = $cached;
    }
    @file_put_contents($cache, '{"ok":false}', LOCK_EX);

    return $data = null;
}

/** ISO-8601 (with +05:30) → "Thu 20 Nov 2026, 11:00 PM". */
function legal_live_ist($iso, $format = 'D j M Y, g:i A')
{
    if (!$iso) {
        return '';
    }
    try {
        $d = new DateTime($iso);
        $d->setTimezone(new DateTimeZone('Asia/Kolkata'));
        return $d->format($format);
    } catch (Exception $e) {
        return '';
    }
}
