var webpack = require('webpack');
const path = require("path");

module.exports = {
    entry: "./js/main.js",
    output: {
        path: path.resolve(__dirname, "./js/dist"),
        filename: "build.js"
    },
    watchOptions: {
        poll: true
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.js'
        },
    },
};