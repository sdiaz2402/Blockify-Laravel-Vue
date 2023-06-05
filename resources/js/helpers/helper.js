import router from 'resources/js/Routes';


const plugin = {
    install(Vue) {
        Vue.prototype.$success_notification = function (message) {
                this.$toasted.success(message, {
                    action: {
                        text: 'Close',
                        onClick: (e, toastObject) => {
                            toastObject.goAway(0);
                        }
                    }
                });
            },

            Vue.prototype.$error_notification = function (error) {
                console.log(error)

                try {
                    if (typeof error === 'string' || error instanceof String) {

                        if (error.includes("401")) {
                            return;
                        } else {
                            this.$toasted.error(error, {
                                action: {
                                    text: 'Close',
                                    onClick: (e, toastObject) => {
                                        toastObject.goAway(0);
                                    }
                                }
                            });
                        }
                    } else if ("response" in error && "status" in error.response && (error.response.status == 401 || error.response.status == 405)) {


                    } else {

                        if (error.includes("401")) {

                        } else {
                            this.$toasted.error(error, {
                                action: {
                                    text: 'Close',
                                    onClick: (e, toastObject) => {
                                        toastObject.goAway(0);
                                    }
                                }
                            });
                        }

                    }
                } catch (e) {
                    console.log(e);
                }

            }

        Vue.prototype.$isJSON = function (obj) {
            return obj !== undefined && obj !== null && obj.constructor == Object;
        }
        Vue.prototype.$auth_check = function (error) {


            if ("response" in error && "status" in error.response && (error.response.status == 401 || error.response.status == 405)) {
                localStorage.removeItem('user');
                document.cookie = 'token=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
                router.push('/login');
            }
            if ("message" in error && error.message == "Unauthenticated") {
                localStorage.removeItem('user');
                document.cookie = 'token=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
                router.push('/login');
            }


            if (error.message.indexOf("401") !== -1) {
                localStorage.removeItem('user');
                document.cookie = 'token=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
                router.push('/login');
            }
        }
    }
}

export default plugin;
