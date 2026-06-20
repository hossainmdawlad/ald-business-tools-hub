<?php
/**
 * Meta Tag Generator Tool
 *
 * @package ALD_Business_Tools_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>

<div class="bth-meta-wrap">
    <h2><?php esc_html_e( 'Meta Tag Generator', 'ald-business-tools-hub' ); ?></h2>
    <p><?php esc_html_e( 'Generate SEO-optimized meta tags for your web pages.', 'ald-business-tools-hub' ); ?></p>

    <form id="bth-meta-tag-form" onsubmit="return false;">
        <div class="bth-form-group">
            <label for="bth-page-title" class="bth-form-label"><?php esc_html_e( 'Page Title', 'ald-business-tools-hub' ); ?></label>
            <input type="text" id="bth-page-title" class="bth-form-input" maxlength="60" placeholder="<?php esc_attr_e( 'Enter page title...', 'ald-business-tools-hub' ); ?>" />
            <span class="bth-char-counter"><span id="bth-page-title-count">0</span>/60</span>
        </div>

        <div class="bth-form-group">
            <label for="bth-page-description" class="bth-form-label"><?php esc_html_e( 'Page Description', 'ald-business-tools-hub' ); ?></label>
            <textarea id="bth-page-description" class="bth-form-textarea" maxlength="160" rows="3" placeholder="<?php esc_attr_e( 'Enter page description...', 'ald-business-tools-hub' ); ?>"></textarea>
            <span class="bth-char-counter"><span id="bth-page-description-count">0</span>/160</span>
        </div>

        <div class="bth-form-group">
            <label for="bth-focus-keyword" class="bth-form-label"><?php esc_html_e( 'Focus Keyword', 'ald-business-tools-hub' ); ?></label>
            <input type="text" id="bth-focus-keyword" class="bth-form-input" placeholder="<?php esc_attr_e( 'Enter focus keyword...', 'ald-business-tools-hub' ); ?>" />
        </div>

        <div class="bth-form-group">
            <label for="bth-author" class="bth-form-label"><?php esc_html_e( 'Author', 'ald-business-tools-hub' ); ?></label>
            <input type="text" id="bth-author" class="bth-form-input" placeholder="<?php esc_attr_e( 'Enter author name...', 'ald-business-tools-hub' ); ?>" />
        </div>

        <div class="bth-form-group">
            <label for="bth-canonical-url" class="bth-form-label"><?php esc_html_e( 'Canonical URL', 'ald-business-tools-hub' ); ?></label>
            <input type="url" id="bth-canonical-url" class="bth-form-input" placeholder="<?php esc_attr_e( 'https://example.com/page', 'ald-business-tools-hub' ); ?>" />
        </div>

        <div class="bth-form-group">
            <label for="bth-robots" class="bth-form-label"><?php esc_html_e( 'Robots', 'ald-business-tools-hub' ); ?></label>
            <select id="bth-robots" class="bth-form-select">
                <option value="index,follow"><?php esc_html_e( 'index, follow', 'ald-business-tools-hub' ); ?></option>
                <option value="noindex,nofollow"><?php esc_html_e( 'noindex, nofollow', 'ald-business-tools-hub' ); ?></option>
                <option value="index,nofollow"><?php esc_html_e( 'index, nofollow', 'ald-business-tools-hub' ); ?></option>
                <option value="noindex,follow"><?php esc_html_e( 'noindex, follow', 'ald-business-tools-hub' ); ?></option>
            </select>
        </div>

        <div class="bth-form-group">
            <label for="bth-og-title" class="bth-form-label"><?php esc_html_e( 'Open Graph Title', 'ald-business-tools-hub' ); ?></label>
            <input type="text" id="bth-og-title" class="bth-form-input" placeholder="<?php esc_attr_e( 'Enter Open Graph title...', 'ald-business-tools-hub' ); ?>" />
        </div>

        <div class="bth-form-group">
            <label for="bth-og-description" class="bth-form-label"><?php esc_html_e( 'Open Graph Description', 'ald-business-tools-hub' ); ?></label>
            <textarea id="bth-og-description" class="bth-form-textarea" rows="3" placeholder="<?php esc_attr_e( 'Enter Open Graph description...', 'ald-business-tools-hub' ); ?>"></textarea>
        </div>

        <div class="bth-form-group">
            <button type="button" id="bth-generate-meta" class="bth-btn bth-btn-primary"><?php esc_html_e( 'Generate Meta Tags', 'ald-business-tools-hub' ); ?></button>
        </div>
    </form>

    <div class="bth-result-box" id="bth-result-box" style="display: none;">
        <div class="bth-form-group">
            <label for="bth-generated-output" class="bth-form-label"><?php esc_html_e( 'Generated Meta Tags', 'ald-business-tools-hub' ); ?></label>
            <textarea id="bth-generated-output" class="bth-form-textarea" rows="12" readonly></textarea>
        </div>
        <div class="bth-form-group">
            <button type="button" id="bth-copy-meta" class="bth-btn bth-btn-secondary"><?php esc_html_e( 'Copy to Clipboard', 'ald-business-tools-hub' ); ?></button>
            <span id="bth-copy-feedback" style="display:none; margin-left:10px; color:green;"><?php esc_html_e( 'Copied!', 'ald-business-tools-hub' ); ?></span>
        </div>
    </div>

    <div class="bth-meta-preview" id="bth-meta-preview" style="display: none;">
        <h3><?php esc_html_e( 'Preview', 'ald-business-tools-hub' ); ?></h3>

        <div class="bth-preview-section">
            <h4><?php esc_html_e( 'Google Search Preview', 'ald-business-tools-hub' ); ?></h4>
            <div class="bth-google-preview">
                <div class="bth-google-title" id="bth-preview-google-title"></div>
                <div class="bth-google-url" id="bth-preview-google-url"></div>
                <div class="bth-google-description" id="bth-preview-google-description"></div>
            </div>
        </div>

        <div class="bth-preview-section">
            <h4><?php esc_html_e( 'Social Media Card Preview', 'ald-business-tools-hub' ); ?></h4>
            <div class="bth-social-preview">
                <div class="bth-social-card">
                    <div class="bth-social-title" id="bth-preview-social-title"></div>
                    <div class="bth-social-description" id="bth-preview-social-description"></div>
                    <div class="bth-social-url" id="bth-preview-social-url"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var pageTitle       = document.getElementById('bth-page-title');
    var pageDesc        = document.getElementById('bth-page-description');
    var focusKeyword    = document.getElementById('bth-focus-keyword');
    var author          = document.getElementById('bth-author');
    var canonicalUrl    = document.getElementById('bth-canonical-url');
    var robots          = document.getElementById('bth-robots');
    var ogTitle         = document.getElementById('bth-og-title');
    var ogDesc          = document.getElementById('bth-og-description');
    var generateBtn     = document.getElementById('bth-generate-meta');
    var copyBtn         = document.getElementById('bth-copy-meta');
    var resultBox       = document.getElementById('bth-result-box');
    var output          = document.getElementById('bth-generated-output');
    var previewSection  = document.getElementById('bth-meta-preview');
    var copyFeedback    = document.getElementById('bth-copy-feedback');

    var titleCount     = document.getElementById('bth-page-title-count');
    var descCount      = document.getElementById('bth-page-description-count');

    // Character counters
    pageTitle.addEventListener('input', function() {
        titleCount.textContent = this.value.length;
    });

    pageDesc.addEventListener('input', function() {
        descCount.textContent = this.value.length;
    });

    function escapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    function generateMetaTags() {
        var title       = pageTitle.value.trim();
        var description = pageDesc.value.trim();
        var keyword     = focusKeyword.value.trim();
        var authorName  = author.value.trim();
        var canonical   = canonicalUrl.value.trim();
        var robotsVal   = robots.value;
        var ogTitleVal  = ogTitle.value.trim() || title;
        var ogDescVal   = ogDesc.value.trim() || description;

        if (!title) {
            alert('<?php echo esc_js( "Please enter a page title.", "ald-business-tools" ); ?>');
            return;
        }

        var tags = '';
        tags += '<meta charset="<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>" />\n';
        tags += '<title>' + escapeHtml(title) + '</title>\n';
        tags += '<meta name="viewport" content="width=device-width, initial-scale=1.0" />\n';

        if (description) {
            tags += '<meta name="description" content="' + escapeHtml(description) + '" />\n';
        }

        if (keyword) {
            tags += '<meta name="keywords" content="' + escapeHtml(keyword) + '" />\n';
        }

        if (authorName) {
            tags += '<meta name="author" content="' + escapeHtml(authorName) + '" />\n';
        }

        tags += '<meta name="robots" content="' + escapeHtml(robotsVal) + '" />\n';

        if (canonical) {
            tags += '<link rel="canonical" href="' + escapeHtml(canonical) + '" />\n';
        }

        // Open Graph tags
        tags += '<meta property="og:title" content="' + escapeHtml(ogTitleVal) + '" />\n';

        if (ogDescVal) {
            tags += '<meta property="og:description" content="' + escapeHtml(ogDescVal) + '" />\n';
        }

        if (canonical) {
            tags += '<meta property="og:url" content="' + escapeHtml(canonical) + '" />\n';
        }

        tags += '<meta property="og:type" content="website" />\n';
        tags += '<meta property="og:site_name" content="<?php echo esc_js( get_bloginfo( "name" ) ); ?>" />\n';

        // Twitter Card tags
        tags += '<meta name="twitter:card" content="summary_large_image" />\n';
        tags += '<meta name="twitter:title" content="' + escapeHtml(ogTitleVal) + '" />\n';

        if (ogDescVal) {
            tags += '<meta name="twitter:description" content="' + escapeHtml(ogDescVal) + '" />\n';
        }

        output.value = tags;
        resultBox.style.display = 'block';

        // Update previews
        updatePreviews(title, description, canonical, ogTitleVal, ogDescVal);
        previewSection.style.display = 'block';
    }

    function updatePreviews(title, description, canonical, ogTitleVal, ogDescVal) {
        var displayUrl = canonical || 'https://example.com/page';

        document.getElementById('bth-preview-google-title').textContent = title;
        document.getElementById('bth-preview-google-url').textContent = displayUrl;
        document.getElementById('bth-preview-google-description').textContent = description || '';

        document.getElementById('bth-preview-social-title').textContent = ogTitleVal;
        document.getElementById('bth-preview-social-description').textContent = ogDescVal || '';
        document.getElementById('bth-preview-social-url').textContent = displayUrl;
    }

    function copyToClipboard() {
        output.select();
        output.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(output.value).then(function() {
            copyFeedback.style.display = 'inline';
            setTimeout(function() {
                copyFeedback.style.display = 'none';
            }, 2000);
        });
    }

    generateBtn.addEventListener('click', generateMetaTags);
    copyBtn.addEventListener('click', copyToClipboard);
})();
</script>
