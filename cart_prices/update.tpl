{assign var="title" value=__("update_prices")}
{** cart_prices_chart section **}

{capture name="mainbox"}

<form action="{""|fn_url}" method="post" class="form-horizontal form-edit" enctype="multipart/form-data" name="selected">
    <input type="hidden" class="cm-no-hide-input" name="fake" value="1" />

    <div id="content_general">
    {if $categories}
        <p class="selection-wrapper">
            <label for="update_prices_by_category">{__(select_category)}</label>
            <select name="updated_option[category]" id="update_prices_by_category">
                <option value="">{__('any')}</option>
                {foreach from=$categories item=category}
                    <option value="{$category.category_id}">{$category.category}</option>
                {/foreach}
            </select>
        </p>
    {else}
        <p class="lowercase">{__("no_categories_with_products")}</p>
    {/if}
    {if $groups}
        <p class="selection-wrapper">
            <label for="update_prices_by_group">{__("select_group")}</label>
            <select name="updated_option[group]" id="update_prices_by_category">
                <option value="">{__('any')}</option>
                {foreach from=$groups item=group}
                    <option value="{$group.group_id}">{$group.name}</option>
                {/foreach}
            </select>
        </p>
    {else}
        <p class="lowercase">{__("no_groups_with_categories")}</p>
    {/if}
    <div class="buttons-container">
        {include file="buttons/save_cancel.tpl" but_text=__("update") but_name="dispatch[cart_prices.update]"}
        {include file="buttons/save_cancel.tpl" but_text=__("preview_update") but_name="dispatch[cart_prices.preview_update_prices]"}
    </div>
    {if $updated_products.products}
        {if !empty($requests.status)}
            {if $requests.status=='preview'}
                <h4>{__("price_update_results")} ({__("preview")})</h4>
            {else}
            <h4>{__("price_update_results")} | {__("download_csv_file_with")} <a href="{$updated_products.file}">{__(price_update_results)}</a></h4>
            {/if}
    {/if}
        <div class="table-responsive-wrapper cart-price-chart">
            <table class="table table-middle table-objects table-responsive" border="1" cellspacing="0" cellpadding="0" border-color: #ccc;">
                <thead>
                    <tr>
                        <th>{__("category")}</th>
                        <th>{__("product")}</th>
                        <th>{__("stock")}</th>
                        <th>{__("old_price")}</th>
                        <th>{__("new_price")}</th>
                        <th>{__("old_foil_price")}</th>
                        <th>{__("new_foil_price")}</th> 
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$updated_products.products  item=product}
                        <tr>
                            <td>{$product.category}</td>
                            <td>{$product.product}</td>
                            <td>{$product.stock}</td>
                            <td>{$product.old_price}</td>
                            <td>{$product.new_price}</td>
                            <td>{$product.old_foil_price}</td>
                            <td>{$product.new_foil_price}</td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    {else if $updated_products.error}
        <div class="text-error">{$updated_products.error}</div>
    {else if $updated_products.update_status}
        <div class="text">{$updated_products.update_status}</div>
    {/if}
    </div>
</form>
{/capture}

{include file="common/mainbox.tpl" title={$title} content=$smarty.capture.mainbox buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons}

{** cart_prices_chart section **}
