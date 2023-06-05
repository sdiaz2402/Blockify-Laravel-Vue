const axios = require('axios');
const cheerio = require('cheerio');


// console.log("Attempting: https://www.cmegroup.com/trading/bitcoin-futures.html");

axios.get('https://www.cmegroup.com/trading/bitcoin-futures.html')
.then(response => {

    // console.log(response);

    // console.log(data);
    const $ = cheerio.load(response.data);
    const divs = $('#latest-trading-activity table tr td');
    // console.log(divs.length);

    var scraped_data = {
        BTC_volume : 0,
        BTC_open_interest : 0,
        BTC_options_open_volume : 0,
        BTC_options_open_interest : 0,
    }

    //17 =

    var index = 0;

    divs.each( function(){
        // console.log($(this).text());
        if(index == 16){
            scraped_data.BTC_volume = $(this).text();
        }
        if(index == 17){
            scraped_data.BTC_open_interest = $(this).text();
        }
        if(index == 25){
            scraped_data.BTC_options_open_volume = $(this).text();
        }
        if(index == 26){
            scraped_data.BTC_options_open_interest = $(this).text();
        }

        // console.log(index);

        index = index + 1;
    })

    //
    var output = JSON.stringify(scraped_data);
    console.log(output);
    process.exit();


})
.catch(err => console.log(err));
