import hljs from 'highlight.js';

export class Highlight {
    constructor(selector = 'pre, code') {
        this.selector = selector;
        this.init();
    }

    init() {
        hljs.highlightAll(this.selector);
    }
}
