{capture name="mainbox"}

<form action="{""|fn_url}" method="post" class="form-horizontal form-edit" enctype="multipart/form-data" name="cart_prices_chart">
    <input type="hidden" class="cm-no-hide-input" name="fake" value="1" />

    <div id="content_general">
        <a class="cm-popover btn hand" href="{"exim.export&section=cart_prices_chart"|fn_url}">{__("export")}</a>
        <a class="cm-popover btn hand" href="{"exim.import&section=cart_prices_chart"|fn_url}">{__("import")}</a>
        <div class="buttons-container">
            {include file="buttons/save_cancel.tpl" but_text="{__("save")}" but_role="submit-link" but_target_form="cart_prices_chart" but_name="dispatch[cart_prices.edit]"}
        </div>
        <div class="table-responsive-wrapper cart-price-chart">
            <table class="table table-middle table-objects table-responsive" border="1" cellspacing="0" cellpadding="0" style="width: 20%; border-color: #ccc;">
            <thead>
                <tr>
                    <th>{__("low_price")}</th>
                    <th>{__("high_price")}</th>
                    <th>{__("price")}</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
                {if $chart}
                    {foreach from=$chart item=item}
                        <tr data-number="{$item.id}">
                            <td><input class="input-small" type="text" name="cart_prices_chart[{$item.id}][low_range]" value="{$item.low_range}" /></td>
                            <td><input class="input-small" type="text" name="cart_prices_chart[{$item.id}][high_range]" value="{$item.high_range}" /></td>
                            <td><input class="input-small" type="text" name="cart_prices_chart[{$item.id}][price]" value="{$item.price}" /></td>
                            <td>{include file="buttons/multiple_buttons.tpl" only_delete="Y"}</td>
                        </tr>
                    {/foreach}
                {/if}
                <tr>
                    <td colspan="4" style="text-align: center;"><input type="button" class="btn btn-primary cm-submit btn-primary" id="add_cart_price_row" value="{__("add")}" /></td>             
                </tr>
            </table>
        </div>
    </div>

    {include file="buttons/save_cancel.tpl" but_text="{__("save")}" but_role="submit-link" but_target_form="cart_prices_chart" but_name="dispatch[cart_prices.edit]"}
</form>

{/capture}

{include file="common/mainbox.tpl" title=__("cart_prices_menu_description") content=$smarty.capture.mainbox select_languages=true}
