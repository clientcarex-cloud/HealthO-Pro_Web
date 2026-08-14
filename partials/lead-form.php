<?php
/**
 * The site's one lead-capture form.
 *
 * Every page's enquiry form and the demo popup are the same fields with a
 * different second column, submit label and footnote — so they all render
 * through lead_form(). script.js binds validation, geo and the AJAX POST to
 * any [data-ajax] form, so nothing here needs page-specific wiring.
 */
require_once __DIR__ . '/site.php';

/** <select> in a .field wrapper. $options are plain strings. */
function form_select(string $name, string $label, array $options, bool $required = false, string $placeholder = 'Select…'): string
{
    $out = '<div class="field"><label>' . h($label);
    if ($required) {
        $out .= ' <span class="req">*</span>';
    }
    $out .= '</label><select name="' . h($name) . '"' . ($required ? ' required' : '') . '>';
    $out .= '<option value="">' . h($placeholder) . '</option>';
    foreach ($options as $o) {
        $out .= '<option>' . h($o) . '</option>';
    }
    return $out . '</select></div>';
}

/** <input> in a .field wrapper. */
function form_input(string $type, string $name, string $label, string $placeholder = '', bool $required = false, string $error = ''): string
{
    $out = '<div class="field"><label>' . h($label);
    if ($required) {
        $out .= ' <span class="req">*</span>';
    }
    $out .= '</label><input type="' . h($type) . '" name="' . h($name) . '" placeholder="' . h($placeholder) . '"'
        . ($required ? ' required' : '') . '>';
    if ($error !== '') {
        $out .= '<span class="err-msg">' . h($error) . '</span>';
    }
    return $out . '</div>';
}

/** The four solutions, as offered in the "Interested In" dropdown. */
function interest_options(string $last = 'Multiple / Not sure'): array
{
    $names = array_column(PRODUCTS, 'short');
    $names[] = $last;
    return $names;
}

/**
 * Render the form.
 *
 * @param array $o  hidden  assoc array of hidden inputs
 *                  aside   raw HTML for the field beside "Mobile Number"
 *                  interest raw HTML for the full-width field under it (or '')
 *                  extra   raw HTML inserted before the message field
 *                  message ['label', 'placeholder', 'required']
 *                  submit  button label
 *                  note    raw HTML footnote
 */
function lead_form(array $o = []): void
{
    $msg = ($o['message'] ?? []) + ['label' => 'Message', 'placeholder' => 'Tell us about your requirements…', 'required' => false];
    $note = $o['note'] ?? 'By submitting, you agree to our <a href="/privacy-policy" class="link-cyan">Privacy Policy</a>.';
    ?>
    <form data-ajax novalidate>
      <div class="form-status"></div>
<?php foreach (($o['hidden'] ?? []) as $name => $value): ?>
      <input type="hidden" name="<?= h($name) ?>" value="<?= h($value) ?>">
<?php endforeach; ?>
      <div class="form-row">
        <?= form_input('text', 'fullName', 'Full Name', 'Your name', true) ?>
        <?= form_input('email', 'email', 'Email', 'you@example.com', true, 'Enter a valid email.') ?>
      </div>
      <div class="form-row">
        <?= form_input('tel', 'phone', 'Mobile Number', '+91 ...', true) ?>
        <?= $o['aside'] ?? form_select('orgType', 'Organization Type', ['Hospital', 'Laboratory', 'Clinic', 'Radiology Center', 'Other']) ?>
      </div>
      <?= $o['interest'] ?? form_select('interest', 'Interested In', interest_options(), false, 'Select a solution…') ?>
      <?= $o['extra'] ?? '' ?>
      <div class="field"><label><?= h($msg['label']) ?><?= $msg['required'] ? ' <span class="req">*</span>' : '' ?></label><textarea name="message" placeholder="<?= h($msg['placeholder']) ?>"<?= $msg['required'] ? ' required' : '' ?>></textarea><?= $msg['required'] ? '<span class="err-msg">Please enter a message.</span>' : '' ?></div>
      <button type="submit" class="btn btn-primary btn-block btn-lg"><?= h($o['submit'] ?? 'Request Demo') ?></button>
      <p class="form-note"><?= $note ?></p>
    </form>
    <?php
}
