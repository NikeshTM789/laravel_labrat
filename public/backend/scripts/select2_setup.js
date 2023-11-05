function select2(placeholder = 'Select an option', s2_class = false, custom_select = false){
    s2_class = (s2_class) ? s2_class : '.select2';
    select_el = $(s2_class);
    select_el.select2({
        placeholder,
        // allowClear: true
    });
    if(custom_select){
        select_el.val('#' + custom_select);
        select_el.trigger('change');
    }
}