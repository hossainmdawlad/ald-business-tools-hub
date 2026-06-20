<?php
/**
 * UTM Link Builder Tool
 *
 * @package   ALD_Business_Tools_Hub
 * @since     1.0.0
 */

// ABSPATH guard
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="bth-utm-wrap">
    <h2><?php esc_html_e( 'UTM Link Builder', 'ald-business-tools-hub' ); ?></h2>

    <form id="bth-utm-form" class="bth-form-group">
        <!-- Website URL -->
        <div class="bth-form-group">
            <label class="bth-form-label" for="bth-utm-url"><?php esc_html_e( 'Website URL', 'ald-business-tools-hub' ); ?> <span class="required">*</span></label>
            <input type="url" id="bth-utm-url" class="bth-form-input" placeholder="https://example.com" required>
        </div>

        <!-- Campaign Source -->
        <div class="bth-form-group">
            <label class="bth-form-label" for="bth-utm-source"><?php esc_html_e( 'Campaign Source', 'ald-business-tools-hub' ); ?></label>
            <input type="text" id="bth-utm-source" class="bth-form-input" list="bth-source-suggestions" placeholder="<?php esc_attr_e( 'Enter or select source', 'ald-business-tools-hub' ); ?>">
            <datalist id="bth-source-suggestions">
                <option value="facebook">
                <option value="twitter">
                <option value="instagram">
                <option value="linkedin">
                <option value="youtube">
                <option value="newsletter">
                <option value="google">
                <option value="bing">
            </datalist>
        </div>

        <!-- Campaign Medium -->
        <div class="bth-form-group">
            <label class="bth-form-label" for="bth-utm-medium"><?php esc_html_e( 'Campaign Medium', 'ald-business-tools-hub' ); ?></label>
            <input type="text" id="bth-utm-medium" class="bth-form-input" list="bth-medium-suggestions" placeholder="<?php esc_attr_e( 'Enter or select medium', 'ald-business-tools-hub' ); ?>">
            <datalist id="bth-medium-suggestions">
                <option value="cpc">
                <option value="banner">
                <option value="email">
                <option value="social">
                <option value="affiliate">
                <option value="organic">
            </datalist>
        </div>

        <!-- Campaign Name -->
        <div class="bth-form-group">
            <label class="bth-form-label" for="bth-utm-campaign"><?php esc_html_e( 'Campaign Name', 'ald-business-tools-hub' ); ?> <span class="required">*</span></label>
            <input type="text" id="bth-utm-campaign" class="bth-form-input" placeholder="<?php esc_attr_e( 'e.g. summer_sale', 'ald-business-tools-hub' ); ?>" required>
        </div>

        <!-- Campaign Term -->
        <div class="bth-form-group">
            <label class="bth-form-label" for="bth-utm-term"><?php esc_html_e( 'Campaign Term', 'ald-business-tools-hub' ); ?></label>
            <input type="text" id="bth-utm-term" class="bth-form-input" placeholder="<?php esc_attr_e( 'Optional — paid keywords', 'ald-business-tools-hub' ); ?>">
        </div>

        <!-- Campaign Content -->
        <div class="bth-form-group">
            <label class="bth-form-label" for="bth-utm-content"><?php esc_html_e( 'Campaign Content', 'ald-business-tools-hub' ); ?></label>
            <input type="text" id="bth-utm-content" class="bth-form-input" placeholder="<?php esc_attr_e( 'Optional — A/B testing', 'ald-business-tools-hub' ); ?>">
        </div>

        <!-- Generate Button -->
        <div class="bth-form-group">
            <button type="submit" class="bth-btn bth-btn-primary"><?php esc_html_e( 'Generate UTM Link', 'ald-business-tools-hub' ); ?></button>
        </div>
    </form>

    <!-- Result Box -->
    <div class="bth-result-box" style="display: none;">
        <label class="bth-form-label" for="bth-utm-result"><?php esc_html_e( 'Generated URL', 'ald-business-tools-hub' ); ?></label>
        <div class="bth-result-row">
            <input type="text" id="bth-utm-result" class="bth-form-input" readonly>
            <button type="button" id="bth-copy-btn" class="bth-btn bth-btn-secondary"><?php esc_html_e( 'Copy', 'ald-business-tools-hub' ); ?></button>
            <button type="button" id="bth-qr-btn" class="bth-btn bth-btn-secondary"><?php esc_html_e( 'QR Code', 'ald-business-tools-hub' ); ?></button>
        </div>
        <div class="bth-utm-qr" id="bth-qr-container" style="display: none; margin-top: 10px; text-align: center;"></div>
    </div>

    <!-- History Section -->
    <div class="bth-utm-history">
        <h3><?php esc_html_e( 'Recent Links', 'ald-business-tools-hub' ); ?></h3>
        <div class="bth-history-header">
            <button type="button" id="bth-clear-history" class="bth-btn bth-btn-secondary" style="display: none;"><?php esc_html_e( 'Clear History', 'ald-business-tools-hub' ); ?></button>
        </div>
        <ul id="bth-history-list" class="bth-history-list"></ul>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
(function() {
    'use strict';

    var form = document.getElementById('bth-utm-form');
    var urlInput = document.getElementById('bth-utm-url');
    var sourceInput = document.getElementById('bth-utm-source');
    var mediumInput = document.getElementById('bth-utm-medium');
    var campaignInput = document.getElementById('bth-utm-campaign');
    var termInput = document.getElementById('bth-utm-term');
    var contentInput = document.getElementById('bth-utm-content');
    var resultBox = document.querySelector('.bth-result-box');
    var resultInput = document.getElementById('bth-utm-result');
    var copyBtn = document.getElementById('bth-copy-btn');
    var qrBtn = document.getElementById('bth-qr-btn');
    var qrContainer = document.getElementById('bth-qr-container');
    var historyList = document.getElementById('bth-history-list');
    var clearHistoryBtn = document.getElementById('bth-clear-history');

    var STORAGE_KEY = 'bth_utm_history';
    var MAX_HISTORY = 10;

    /**
     * Get history from localStorage
     */
    function getHistory() {
        try {
            var data = localStorage.getItem(STORAGE_KEY);
            return data ? JSON.parse(data) : [];
        } catch (e) {
            return [];
        }
    }

    /**
     * Save history to localStorage
     */
    function saveHistory(history) {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(history));
        } catch (e) {
            // Storage full or unavailable
        }
    }

    /**
     * Add entry to history
     */
    function addToHistory(url, timestamp) {
        var history = getHistory();
        history.unshift({ url: url, timestamp: timestamp || new Date().toISOString() });
        if (history.length > MAX_HISTORY) {
            history = history.slice(0, MAX_HISTORY);
        }
        saveHistory(history);
        renderHistory();
    }

    /**
     * Render history list
     */
    function renderHistory() {
        var history = getHistory();
        historyList.innerHTML = '';

        if (history.length === 0) {
            clearHistoryBtn.style.display = 'none';
            var emptyLi = document.createElement('li');
            emptyLi.className = 'bth-history-empty';
            emptyLi.textContent = 'No links generated yet.';
            historyList.appendChild(emptyLi);
            return;
        }

        clearHistoryBtn.style.display = 'inline-block';

        history.forEach(function(entry, index) {
            var li = document.createElement('li');
            li.className = 'bth-history-item';

            var urlSpan = document.createElement('span');
            urlSpan.className = 'bth-history-url';
            urlSpan.textContent = entry.url;
            urlSpan.title = entry.url;

            var copyBtnEl = document.createElement('button');
            copyBtnEl.type = 'button';
            copyBtnEl.className = 'bth-btn bth-btn-secondary bth-btn-sm';
            copyBtnEl.textContent = 'Copy';
            copyBtnEl.addEventListener('click', function() {
                copyToClipboard(entry.url);
            });

            li.appendChild(urlSpan);
            li.appendChild(copyBtnEl);
            historyList.appendChild(li);
        });
    }

    /**
     * Copy text to clipboard
     */
    function copyToClipboard(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).catch(function() {
                fallbackCopy(text);
            });
        } else {
            fallbackCopy(text);
        }
    }

    /**
     * Fallback copy method
     */
    function fallbackCopy(text) {
        var tempInput = document.createElement('input');
        tempInput.value = text;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
    }

    /**
     * Build UTM URL
     */
    function buildUTMUrl() {
        var url = urlInput.value.trim();
        var source = sourceInput.value.trim();
        var medium = mediumInput.value.trim();
        var campaign = campaignInput.value.trim();
        var term = termInput.value.trim();
        var content = contentInput.value.trim();

        if (!url || !campaign) {
            return null;
        }

        // Ensure URL has a protocol
        if (!/^https?:\/\//i.test(url)) {
            url = 'https://' + url;
        }

        // Remove trailing slash for clean query appending
        url = url.replace(/\/+$/, '');

        var params = [];
        if (source) {
            params.push('utm_source=' + encodeURIComponent(source));
        }
        if (medium) {
            params.push('utm_medium=' + encodeURIComponent(medium));
        }
        if (campaign) {
            params.push('utm_campaign=' + encodeURIComponent(campaign));
        }
        if (term) {
            params.push('utm_term=' + encodeURIComponent(term));
        }
        if (content) {
            params.push('utm_content=' + encodeURIComponent(content));
        }

        return url + (params.length ? '?' + params.join('&') : '');
    }

    /**
     * Generate QR Code
     */
    function generateQR(url) {
        qrContainer.innerHTML = '';
        qrContainer.style.display = 'block';

        if (typeof QRCode !== 'undefined') {
            QRCode.toCanvas(qrContainer, url, {
                width: 200,
                margin: 2,
                color: {
                    dark: '#000000',
                    light: '#ffffff'
                }
            }, function(error) {
                if (error) {
                    qrContainer.innerHTML = '';
                    qrContainer.style.display = 'none';
                }
            });
        } else {
            qrContainer.style.display = 'none';
        }
    }

    // Form submit handler
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        var generatedUrl = buildUTMUrl();
        if (!generatedUrl) {
            alert('Please fill in the required fields: Website URL and Campaign Name.');
            return;
        }

        resultInput.value = generatedUrl;
        resultBox.style.display = 'block';
        qrContainer.style.display = 'none';

        addToHistory(generatedUrl);
    });

    // Copy button handler
    copyBtn.addEventListener('click', function() {
        if (resultInput.value) {
            copyToClipboard(resultInput.value);
            var originalText = copyBtn.textContent;
            copyBtn.textContent = 'Copied!';
            setTimeout(function() {
                copyBtn.textContent = originalText;
            }, 1500);
        }
    });

    // QR button handler
    qrBtn.addEventListener('click', function() {
        if (resultInput.value) {
            generateQR(resultInput.value);
        }
    });

    // Clear history handler
    clearHistoryBtn.addEventListener('click', function() {
        if (confirm('Are you sure you want to clear all history?')) {
            localStorage.removeItem(STORAGE_KEY);
            renderHistory();
        }
    });

    // Initialize history on load
    renderHistory();
})();
</script>
