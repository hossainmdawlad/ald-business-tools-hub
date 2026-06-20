/**
 * ALD Business Tools Hub — Frontend JavaScript
 */

(function(){
    'use strict';

    // === Utility: Copy to clipboard ===
    window.bthCopyToClipboard = function(text, btn) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(function(){
                showCopyFeedback(btn);
            });
        } else {
            var ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            showCopyFeedback(btn);
        }
    };

    function showCopyFeedback(btn) {
        if (!btn) return;
        var original = btn.textContent;
        btn.textContent = '✓ Copied!';
        btn.style.color = '#16a34a';
        setTimeout(function(){
            btn.textContent = original;
            btn.style.color = '';
        }, 1500);
    }

    // === Utility: Format file size ===
    window.bthFormatSize = function(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    };

    // === Utility: Debounce ===
    function debounce(fn, delay) {
        var timer;
        return function() {
            var args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function(){ fn.apply(null, args); }, delay);
        };
    }

})();
