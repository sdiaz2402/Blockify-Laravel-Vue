var os = require('os');
const hosti = os.hostname() === "blockify.local" ? "http://blockify.local" : "https://dev.blockify.com";
const hostApi = os.hostname() === "blockify.local" ? "http://blockify.local/api" : "https://dev.blockify.com/api";
const hostApiStorage = os.hostname() === "blockify.local" ? "http://blockify.local/storage" : "https://dev.blockify.com/storaage";
const clientApi = os.hostname() === "blockify.local" ? "http://blockify.local/api" : "https://dev.blockify.com/api";



const portApi = os.hostname() === "development" ? "" : "";
// const baseURLApi = `${hostApi}${portApi ? `:${portApi}` : ``}`;
const baseURLApi = `${hostApi}`;
const baseURL = `${hosti}`;
const secret = "MnZhlxnQm2h8EOllkpIP5H1gDqlWRJ7AZlVGZKdW";
const client = 2;
export default {
  hostApi,
  portApi,
  secret,
  client,
  hostApiStorage,
  headers: {
    "Access-Control-Allow-Origin": clientApi,
      "Access-Control-Allow-Credentials":true,
      'Content-Type': 'application/json',
      'withCredentials': true,
  },
  baseURL,
  baseURLApi,
  // remote: "http://app.entri.ai",
  remote: hostApi,
  isBackend: process.env.VUE_APP_BACKEND,
  auth: {
      email: 'info@blockify.com',
      password: '1234',
  }
};
