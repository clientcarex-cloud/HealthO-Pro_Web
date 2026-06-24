const fs = require('fs');
const jsdom = require("jsdom");
const { JSDOM } = jsdom;

const html = fs.readFileSync('pricing.html', 'utf8');
const scriptCode = fs.readFileSync('script.js', 'utf8');

const dom = new JSDOM(html, { runScripts: "outside-only" });
const window = dom.window;
const document = window.document;

// Mock some APIs missing in JSDOM
window.IntersectionObserver = class {
    constructor() {}
    observe() {}
    unobserve() {}
};
window.requestAnimationFrame = (cb) => setTimeout(cb, 16);

try {
    window.eval(scriptCode);
    console.log("Script executed without errors");
    
    // Simulate DOMContentLoaded
    const event = document.createEvent('Event');
    event.initEvent('DOMContentLoaded', true, true);
    document.dispatchEvent(event);
    
    console.log("DOMContentLoaded dispatched");
    
    // Trigger slider input
    const slider = document.getElementById('globalUserSlider');
    if (slider) {
        slider.value = 25;
        const inputEvent = document.createEvent('Event');
        inputEvent.initEvent('input', true, true);
        slider.dispatchEvent(inputEvent);
        console.log("Slider input dispatched");
        
        // Check if values updated
        const firstCard = document.querySelector('.price-card');
        console.log("First card calc-base text:", firstCard.querySelector('.calc-base').textContent);
    } else {
        console.log("Slider not found!");
    }
} catch (e) {
    console.error(e);
}
