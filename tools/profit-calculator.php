<?php
/**
 * Profit Margin Calculator Tool
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="bth-profit-wrap">
    <form id="bth-profit-form" class="bth-form">
        <div class="bth-form-group">
            <label for="bth-profit-cost" class="bth-form-label"><?php esc_html_e( 'Cost Price', 'ald-business-tools' ); ?></label>
            <input type="number" id="bth-profit-cost" class="bth-form-input" placeholder="<?php esc_attr_e( 'Enter cost price', 'ald-business-tools' ); ?>" step="0.01" min="0" required>
        </div>
        <div class="bth-form-group">
            <label for="bth-profit-selling" class="bth-form-label"><?php esc_html_e( 'Selling Price', 'ald-business-tools' ); ?></label>
            <input type="number" id="bth-profit-selling" class="bth-form-input" placeholder="<?php esc_attr_e( 'Enter selling price', 'ald-business-tools' ); ?>" step="0.01" min="0" required>
        </div>
        <div class="bth-form-group">
            <label for="bth-profit-fixed" class="bth-form-label"><?php esc_html_e( 'Fixed Costs (optional)', 'ald-business-tools' ); ?></label>
            <input type="number" id="bth-profit-fixed" class="bth-form-input" placeholder="<?php esc_attr_e( 'Enter fixed costs for breakeven', 'ald-business-tools' ); ?>" step="0.01" min="0" value="0">
        </div>
        <div class="bth-form-actions">
            <button type="submit" class="bth-btn bth-btn-primary"><?php esc_html_e( 'Calculate', 'ald-business-tools' ); ?></button>
            <button type="button" id="bth-profit-clear" class="bth-btn bth-btn-secondary"><?php esc_html_e( 'Clear', 'ald-business-tools' ); ?></button>
        </div>
    </form>
    <div id="bth-profit-result" class="bth-result-box" style="display:none;">
        <div class="bth-profit-row">
            <span class="bth-profit-label"><?php esc_html_e( 'Profit / Loss:', 'ald-business-tools' ); ?></span>
            <span id="bth-profit-amount" class="bth-profit-value"></span>
        </div>
        <div class="bth-profit-row">
            <span class="bth-profit-label"><?php esc_html_e( 'Profit Margin:', 'ald-business-tools' ); ?></span>
            <span id="bth-profit-margin" class="bth-profit-value"></span>
        </div>
        <div class="bth-profit-row">
            <span class="bth-profit-label"><?php esc_html_e( 'Markup:', 'ald-business-tools' ); ?></span>
            <span id="bth-profit-markup" class="bth-profit-value"></span>
        </div>
        <div class="bth-profit-row" id="bth-profit-breakeven-row" style="display:none;">
            <span class="bth-profit-label"><?php esc_html_e( 'Breakeven Quantity:', 'ald-business-tools' ); ?></span>
            <span id="bth-profit-breakeven" class="bth-profit-value"></span>
        </div>
    </div>
</div>
<script>
(function(){
    var form = document.getElementById('bth-profit-form');
    var result = document.getElementById('bth-profit-result');
    var amountEl = document.getElementById('bth-profit-amount');
    var marginEl = document.getElementById('bth-profit-margin');
    var markupEl = document.getElementById('bth-profit-markup');
    var breakevenEl = document.getElementById('bth-profit-breakeven');
    var breakevenRow = document.getElementById('bth-profit-breakeven-row');

    form.addEventListener('submit', function(e){
        e.preventDefault();
        var cost = parseFloat(document.getElementById('bth-profit-cost').value) || 0;
        var selling = parseFloat(document.getElementById('bth-profit-selling').value) || 0;
        var fixed = parseFloat(document.getElementById('bth-profit-fixed').value) || 0;

        var profit = selling - cost;
        var margin = cost > 0 ? (profit / selling) * 100 : 0;
        var markup = cost > 0 ? (profit / cost) * 100 : 0;

        amountEl.textContent = (profit >= 0 ? '+' : '') + profit.toFixed(2);
        amountEl.className = 'bth-profit-value ' + (profit >= 0 ? 'bth-profit-positive' : 'bth-profit-negative');

        marginEl.textContent = margin.toFixed(2) + '%';
        marginEl.className = 'bth-profit-value ' + (margin >= 0 ? 'bth-profit-positive' : 'bth-profit-negative');

        markupEl.textContent = markup.toFixed(2) + '%';
        markupEl.className = 'bth-profit-value ' + (markup >= 0 ? 'bth-profit-positive' : 'bth-profit-negative');

        if (fixed > 0 && profit > 0) {
            breakevenRow.style.display = '';
            breakevenEl.textContent = Math.ceil(fixed / profit) + ' units';
        } else {
            breakevenRow.style.display = 'none';
        }

        result.style.display = 'block';
    });

    document.getElementById('bth-profit-clear').addEventListener('click', function(){
        form.reset();
        result.style.display = 'none';
    });
})();
</script>
