const axios = require('axios');
const puppeteer = require('puppeteer');
const cheerio = require('cheerio');


// console.log("Attempting: https://www.cmegroup.com/trading/bitcoin-futures.html");


const vgmUrl = 'https://www.cmegroup.com/trading/bitcoin-futures.html';


(async () => {
  const browser = await puppeteer.launch({
    headless: true,
    args: [
    "--no-sandbox",
    "--disable-gpu",
    ]
});
  const page = await browser.newPage();



  await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36');



  await page.goto(vgmUrl);


  const content = await page.content();

//   console.log(content);

  const $ = cheerio.load(content);
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
            scraped_data.BTC_volume = parseFloat($(this).text().replace(",",""));
        }
        if(index == 17){
            scraped_data.BTC_open_interest = parseFloat($(this).text().replace(",",""));
        }
        if(index == 25){
            scraped_data.BTC_options_open_volume = parseFloat($(this).text().replace(",",""));
        }
        if(index == 26){
            scraped_data.BTC_options_open_interest = parseFloat($(this).text().replace(",",""));
        }

        // console.log(index);

        index = index + 1;
    })

    //
    var output = JSON.stringify(scraped_data);
    console.log(output);
    process.exit();


})().catch(err => {
    console.log(err);
    process.exit();
});



