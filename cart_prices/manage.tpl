{** cart_prices section **}

{capture name="mainbox"}

<div class="table-responsive-wrapper edit-links-wrapper">
    <a class="btn btn-primary btn-primary" href="{"cart_prices.edit"|fn_url}">{__("edit")}</a>
    <a class="cm-popover btn hand" href="{"exim.export&section=cart_prices_chart"|fn_url}">{__("export")}</a>
    <a class="cm-popover btn hand" href="{"exim.import&section=cart_prices_chart"|fn_url}">{__("import")}</a>
    <a class="btn btn-primary btn-primary" href="{"cart_prices.update"|fn_url}">{__("update_prices")}</a>
</div>
            
{if $chart}
    <div class="table-responsive-wrapper cart-price-chart">
        <table class="table table-middle table-objects table-responsive" border="1" cellspacing="0" cellpadding="0" style="width: 20%; border-color: #ccc;">
        <thead>
            <tr>
                <th>{__("low_price")}</th>
                <th>{__("high_price")}</th>
                <th>{__("price")}</th>
            </tr>
        </thead>
        {foreach from=$chart item=item}
            <tr>
                <td>{$item.low_range}</td>
                <td>{$item.high_range}</td>
                <td>{$item.price}</td>
            </tr>
        {/foreach}
        </table>
    </div>
{else}
    <p class="no-items">{__("no_data")}</p>
{/if}

{/capture}

{include file="common/mainbox.tpl" title=__("cart_prices_menu") content=$smarty.capture.mainbox select_languages=true}

{** cart_prices section **}
