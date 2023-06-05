import moment from 'moment';
import config from 'resources/js/config';

export default {
  ago: (date) => {
    if (date != '') {
      var dat = moment(date);
      if (dat) {
        return dat.fromNow(false);
      }
    }
    return '';
  },
  uppercase: (input) => {
    return input.toUpperCase();
  },
  capitalize: (value) => {
    if (!value) return '';
    value = value.toString();
    return value.charAt(0).toUpperCase() + value.slice(1);
  },
  tickers: (value) => {
    var output = [];
    var tickers = value.split(' ');
    for (var ticker of tickers) {
      if (ticker != '') {
        output.push('$' + ticker);
      }
    }
    var result = output.join(',');
    return result;
  },
  display_image: (value) => {
    if (!value) return '';
    value = value.toString();
    let url = config.hostApiStorage + '/';
    // let url="http://192.168.0.113/storage/";
    return url + value;
  },
  convert_to_int: (value) => {
    return parseInt(value);
  },

  truncate: (input, length = 100) => {
    if (input.length > length) {
      return input.substring(0, length) + '...';
    }
    return input;
  },
  calculate_performance: (last, bought) => {
    if (isNaN(last) || isNaN(bought)) {
      return 0;
    }

    if (bought == 0) {
      return 0;
    }

    if (bought == null || bought == undefined) {
      return 0;
    }

    var _last = parseFloat(last);
    var _bought = parseFloat(bought);

    var per = (_last - _bought) / _bought;

    per = per * 100;

    return per.toFixed(2);
  },

  format_number: (value) => {
    if (isNaN(value)) {
      return value;
    } else {
      if (value > 1000) {
        return parseFloat(value).toFixed(0);
      } else if (value > 1) {
        return parseFloat(value).toFixed(2);
      } else {
        return parseFloat(value).toFixed(4);
      }
    }
  },

  format_small_number: (value) => {
    if (isNaN(value)) {
      return value;
    } else {
      return parseFloat(value * 100).toFixed(2);
    }
  },

  format_percentage: (value, plus = false) => {
    var output = null;
    if (isNaN(value)) {
      output = value;
    } else {
      output = value * 100;

      if (Math.abs(output) > 10) {
        output = parseFloat(output).toFixed(0);
      } else if (Math.abs(output) > 10) {
        output = parseFloat(output).toFixed(1);
      } else {
        output = parseFloat(output).toFixed(2);
      }

      var parts = output.toString().split('.');
      parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');

      output = parts.join('.');

      if (plus) {
        if (value > 0) {
          output = '+' + output;
        } else if (value < 0) {
          // output = "-"+output;
        }
      }
      return output;
    }
  },
  address_ellipsis: (value) => {
    if (value) {
      return value.substring(0, 4) + '...' + value.substring(value.length - 4);
    } else {
      return '';
    }
  },
  status_type: function (type) {
    if (type !== undefined && type !== null) {
      switch (type.toString().toLowerCase()) {
        case 'valid':
          return 'badge-green';
        case 'active':
          return 'badge-green';
        case 'revoked':
          return 'badge-red';
        default:
          return '';
      }
    }
  },
  admin_type: function (type) {
    if (type !== undefined && type !== null) {
      switch (type.toString().toLowerCase()) {
        case '1':
          return 'badge-green';
        default:
          return '';
      }
    }
  },
  clean_formatting: function (value) {
    //parts[0].replace(/.(A-Z)/g, " ");
  },
  int_str: function (type) {
    if (type !== undefined && type !== null) {
      switch (type.toString().toLowerCase()) {
        case '1':
          return 'yes';
        default:
          return '';
      }
    }
  },
  source_type: function (type) {
    if (type !== undefined && type !== null) {
      switch (type.toString().toLowerCase()) {
        case 'nfc':
          return 'badge-green';
        case 'cloud':
          return 'badge-blue';
        case 'code':
          return 'badge-red';
          s;
        case 'unit':
          return 'badge-red';
        default:
          return 'badge-gray';
      }
    }
  },
  format_price(value, plus = false, dollar = false, thousands = false) {
    var acro = '';
    var output = '';
    if (Math.abs(value) > 1000000000000) {
      value = value / 1000000000000;
      value = parseFloat(value).toFixed(1);
      acro = 'T';
    } else if (Math.abs(value) > 1000000000) {
      value = value / 1000000000;
      if (value > 10) {
        value = parseFloat(value).toFixed(0);
      } else {
        value = parseFloat(value).toFixed(1);
      }
      acro = 'B';
    } else if (Math.abs(value) > 1000000) {
      value = value / 1000000;
      if (value > 10) {
        value = parseFloat(value).toFixed(0);
      } else {
        value = parseFloat(value).toFixed(1);
      }

      acro = 'M';
    } else if (Math.abs(value) > 1000) {
      if (thousands) {
        value = value / 1000;
        if (value > 10) {
          value = parseFloat(value).toFixed(0);
        } else {
          value = parseFloat(value).toFixed(2);
        }
        acro = 'k';
      } else {
        value = parseFloat(value).toFixed(0);
      }
    } else if (Math.abs(value) > 100) {
      value = parseFloat(value).toFixed(0);
    } else if (Math.abs(value) > 10) {
      value = parseFloat(value).toFixed(2);
    } else if (Math.abs(value) > 1) {
      value = parseFloat(value).toFixed(2);
    } else {
      value = parseFloat(value).toFixed(4);
    }
    var parts = value.toString().split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');

    output = parts.join('.') + acro;

    if (dollar) {
      output = '$' + output;
    }

    if (plus) {
      if (value > 0) {
        output = '+' + output;
      } else if (value < 0) {
        // output = "-"+output;
      }
    }

    return output;
  },
  color_number: function (number) {
    if (number !== undefined && number !== null) {
      if (isNaN(number)) {
        return '';
      } else {
        if (number > 0) {
          return 'text-green-700';
        } else if (number < 0) {
          return 'text-red-700';
        } else {
          return '';
        }
      }
    }
  },
  color_number_style: function (number, inverse = false) {
    if (number !== undefined && number !== null) {
      if (isNaN(number)) {
        return '';
      } else {
        if (inverse) {
          number = number * -1;
        }
        if (number > 0) {
          return 'color:#148d25';
          return 'color:#148d25!important';
        } else if (number < 0) {
          return 'color:#f55d5d';
          return 'color:#f55d5d!important';
        } else {
          return '';
        }
      }
    } else {
      console.log('Error on Color Style');
    }
  },

  message_type: function (type) {
    if (type !== undefined && type !== null) {
      switch (type.toString().toLowerCase()) {
        case 'sell':
          return 'badge-red';
          break;
        case 'buy':
          return 'badge-green';
          break;
        default:
          return '';
          break;
      }
    }
  },
};
