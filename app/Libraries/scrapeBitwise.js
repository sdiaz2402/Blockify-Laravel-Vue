const puppeteer = require('puppeteer');
const cheerio = require('cheerio');

const vgmUrl = 'https://www.bitwiseinvestments.com/funds/Bitwise-10';

var output = {market:0,nav:0,premium:0};

(async () => {
  const browser = await puppeteer.launch({
    headless: true,
    args: [
    "--no-sandbox",
    "--disable-gpu",
    ]
});
  const page = await browser.newPage();

  await page.goto(vgmUrl);


  const content = await page.content();

  const $ = cheerio.load(content);

  const divs =  $('div').filter(function() {
    return $(this).text().trim() === 'Market Price*';
  }).next().text();


  const divs_2 =  $('div').filter(function() {
    return $(this).text().trim() === 'Intraday NAV**';
  }).next().text();

//   console.log(divs_2);

  output.market = divs.replace("$","");
  output.nav = divs_2.replace("$","");

//   console.log(divs);
  output.premium = parseFloat(output.market)/parseFloat(output.nav);

  console.log(JSON.stringify(output));

  await browser.close();

  process.exit();
})();
