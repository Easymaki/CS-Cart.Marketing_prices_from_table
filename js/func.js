(function($, _) {
    $(document).ready(function() {
        $(_.doc).on('click', '#add_cart_price_row', function() {
          var self = $(this);
          var num = self.closest('tr').prev().data('number') + 1;
          var html = '<tr data-number="' + num + '">'
                        +'<td><input class="input-small" type="text" name="cart_prices_chart[' + num + '][low_range]" value=""></td>'
                        +'<td><input class="input-small" type="text" name="cart_prices_chart[' + num + '][high_range]" value=""></td>'
                        +'<td><input class="input-small" type="text" name="cart_prices_chart[' + num + '][price]" value=""></td>'
                        +'<td>&nbsp;</td>'
                      +'</tr>';
          self.closest('tr').before(html);
        });
    });
}(Tygh.$, Tygh));