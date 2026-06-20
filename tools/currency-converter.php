<?php
/**
 * Currency Converter Tool Template
 *
 * @var WP_Post $tool
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$currencies = array(
    'USD' => 'USD — US Dollar',
    'EUR' => 'EUR — Euro',
    'GBP' => 'GBP — British Pound',
    'BDT' => 'BDT — Bangladeshi Taka',
    'INR' => 'INR — Indian Rupee',
    'JPY' => 'JPY — Japanese Yen',
    'CNY' => 'CNY — Chinese Yuan',
    'AUD' => 'AUD — Australian Dollar',
    'CAD' => 'CAD — Canadian Dollar',
    'CHF' => 'CHF — Swiss Franc',
    'SAR' => 'SAR — Saudi Riyal',
    'AED' => 'AED — UAE Dirham',
    'MYR' => 'MYR — Malaysian Ringgit',
    'SGD' => 'SGD — Singapore Dollar',
    'THB' => 'THB — Thai Baht',
    'PKR' => 'PKR — Pakistani Rupee',
    'KRW' => 'KRW — South Korean Won',
);
?>
<div class="bth-currency-wrap">
    <form id="bth-currency-form" class="bth-currency-form">
        <div class="bth-form-group">
            <label class="bth-form-label" for="bth-currency-amount"><?php esc_html_e( 'Amount', 'ald-business-tools' ); ?></label>
            <input type="number" id="bth-currency-amount" class="bth-form-input" value="1" min="0" step="any" />
        </div>

        <div class="bth-form-group bth-currency-from-group">
            <label class="bth-form-label" for="bth-currency-from"><?php esc_html_e( 'From', 'ald-business-tools' ); ?></label>
            <select id="bth-currency-from" class="bth-form-select">
                <?php foreach ( $currencies as $code => $label ) : ?>
                    <option value="<?php echo esc_attr( $code ); ?>" <?php selected( 'USD', $code ); ?>><?php echo esc_html( $label ); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="bth-form-group bth-currency-swap-group">
            <button type="button" id="bth-currency-swap" class="bth-btn bth-btn-swap" title="<?php esc_attr_e( 'Swap currencies', 'ald-business-tools' ); ?>">
                &#8644;
            </button>
        </div>

        <div class="bth-form-group bth-currency-to-group">
            <label class="bth-form-label" for="bth-currency-to"><?php esc_html_e( 'To', 'ald-business-tools' ); ?></label>
            <select id="bth-currency-to" class="bth-form-select">
                <?php foreach ( $currencies as $code => $label ) : ?>
                    <option value="<?php echo esc_attr( $code ); ?>" <?php selected( 'BDT', $code ); ?>><?php echo esc_html( $label ); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="bth-form-group">
            <button type="submit" class="bth-btn bth-btn-primary"><?php esc_html_e( 'Convert', 'ald-business-tools' ); ?></button>
        </div>
    </form>

    <div id="bth-currency-loading" class="bth-currency-loading" style="display:none;">
        <span class="bth-spinner"></span>
    </div>

    <div id="bth-currency-result" class="bth-result-box" style="display:none;">
        <div class="bth-currency-result"></div>
        <div class="bth-currency-rate"></div>
    </div>
</div>

<script>
(function() {
    var form      = document.getElementById('bth-currency-form');
    var amountEl  = document.getElementById('bth-currency-amount');
    var fromEl    = document.getElementById('bth-currency-from');
    var toEl      = document.getElementById('bth-currency-to');
    var swapBtn   = document.getElementById('bth-currency-swap');
    var loadingEl = document.getElementById('bth-currency-loading');
    var resultEl  = document.getElementById('bth-currency-result');
    var resultBox = resultEl.querySelector('.bth-currency-result');
    var rateBox   = resultEl.querySelector('.bth-currency-rate');

    swapBtn.addEventListener('click', function() {
        var tmp = fromEl.value;
        fromEl.value = toEl.value;
        toEl.value = tmp;
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        var amount = parseFloat(amountEl.value);
        if (isNaN(amount) || amount < 0) {
            amount = 0;
        }

        loadingEl.style.display = '';
        resultEl.style.display  = 'none';

        var data = new FormData();
        data.append('action', 'bth_currency_convert');
        data.append('nonce', bthData.nonce);
        data.append('from', fromEl.value);
        data.append('to', toEl.value);
        data.append('amount', amount);

        fetch(bthData.ajaxUrl, {
            method: 'POST',
            body: data
        })
        .then(function(response) { return response.json(); })
        .then(function(json) {
            loadingEl.style.display = 'none';

            if (json.success) {
                var d = json.data;
                resultBox.innerHTML = '<strong>' + d.amount + ' ' + d.from + '</strong> = <strong>' + d.converted + ' ' + d.to + '</strong>';
                rateBox.textContent = '1 ' + d.from + ' = ' + d.rate + ' ' + d.to;
                resultEl.style.display = '';
            } else {
                var msg = json.data && json.data.message ? json.data.message : 'Conversion failed. Please try again.';
                resultBox.innerHTML = '<span class="bth-error">' + msg + '</span>';
                rateBox.textContent = '';
                resultEl.style.display = '';
            }
        })
        .catch(function() {
            loadingEl.style.display = 'none';
            resultBox.innerHTML = '<span class="bth-error">Request failed. Please try again.</span>';
            rateBox.textContent = '';
            resultEl.style.display = '';
        });
    });
})();
</script>
