import { DashboardThemes } from 'resources/js/store/layout';

export default {
    data: () => {
        return {

        }
    },
  methods: {
    decodeHtml(html) {
      let txt = document.createElement("textarea");
      txt.innerHTML = html;
      return txt.value;
    }
  }
};
