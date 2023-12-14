{*
 * PrestaChamps
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Commercial License
 * you can't distribute, modify or sell this code
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file
 * If you need help please contact leo@prestachamps.com
 *
 * @author    Mailchimp
 * @copyright Mailchimp
 * @license   commercial
 *}
<div class="panel">
    <div class="panel-body">
        <div class="btn-group" role="group">
            <a href="#" class="btn btn-primary img-start"
               onclick='regenerateProductsExtra({$productId}); return false;'>
                <i class="icon icon-retweet" aria-hidden="true"></i>
                {l s='Sync product' mod='mailchimppro'}
            </a>
        </div>
    </div>
</div>
<script>
    function regenerateProductsExtra(productId) {
        $.ajax({
            type: "POST",
            url: '{$regenerateLink}',
            data: {
                action: 'syncProduct',
                productId: productId,
            }, success: function (response) {
                alert(response.result);
                // alert('Product synced');
            },
        });
    }
</script>