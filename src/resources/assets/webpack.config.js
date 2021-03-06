var webpack = require('webpack');
const path = require("path");

module.exports = {
    entry: {
        back: "./js/back.js",
        front: "./js/front.js",
    },
    output: {
        path: path.resolve(__dirname, "./js/dist"),
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

/*module.exports = {
    entry: "./js/front.js",
    output: {
        path: path.resolve(__dirname, "./js/dist"),
        filename: "front.js"
    },
    watchOptions: {
        poll: true
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.js'
        },
    },
};*/