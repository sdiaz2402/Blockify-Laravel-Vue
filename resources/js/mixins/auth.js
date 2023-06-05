import jwt from "jsonwebtoken";
import config from "resources/js/config";
import { globalStore } from 'resources/js/main.js'
import { serverBus } from "resources/js/main";

export function isAuthenticated(token) {
    try {

        return true;

        // We check if app runs with backend mode
        if (globalStore.user == undefined || globalStore.user == null) {
            if (localStorage.getItem('user') != undefined && localStorage.getItem('user') != null) {
                globalStore.user = JSON.parse(localStorage.getItem('user'));
                globalStore.user_id = globalStore.user.id;
            } else {
                return false;
            }
        }

        return token

    } catch (e) {
        console.log(e);
        return false;
    }
}

export const AuthMixin = {
    methods: {
        isAuthenticated
    }
};
