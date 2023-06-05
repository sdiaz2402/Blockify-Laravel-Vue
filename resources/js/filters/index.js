import filters from './filters';


export default {
     install(Vue) {
         Vue.filter('uppercase', filters.uppercase);
         Vue.filter('capitalize', filters.capitalize);
         Vue.filter('tickers', filters.tickers);
         Vue.filter('display_image', filters.display_image);
         Vue.filter('convert_to_int', filters.convert_to_int);
         Vue.filter('status_type', filters.status_type);
         Vue.filter('source_type', filters.source_type);
         Vue.filter('admin_type', filters.admin_type);
         Vue.filter('int_str', filters.int_str);
         Vue.filter('format_number', filters.format_number);
         Vue.filter('translate_permissions', filters.translate_permissions);
         Vue.filter('truncate', filters.truncate);
         Vue.filter('color_number', filters.color_number);
         Vue.filter('format_percentage', filters.format_percentage);
         Vue.filter('format_price', filters.format_price);
         Vue.filter('color_number_style', filters.color_number_style);
         Vue.filter('format_small_number', filters.format_small_number);
         Vue.filter('clean_formatting', filters.clean_formatting);
         Vue.filter('calculate_performance', filters.calculate_performance);
         Vue.filter('ago', filters.ago);
         Vue.filter('address_ellipsis', filters.address_ellipsis);
     }
}
