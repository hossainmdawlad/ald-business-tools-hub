/**
 * ALD Business Tools Hub — Admin JavaScript
 */

(function($){
    'use strict';

    $(document).ready(function(){
        // Show/hide tool-specific fields based on tool type selection
        var $toolType = $('#bth_tool_type');
        if ($toolType.length) {
            $toolType.on('change', function(){
                var type = $(this).val();
                $('.bth-tool-type-field').hide();
                if (type && type !== 'custom') {
                    // Could show type-specific fields here in the future
                }
            }).trigger('change');
        }
    });

})(jQuery);
