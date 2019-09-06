/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import Vue from 'vue'
import VueRouter from 'vue-router'
import {routes} from './routes'
import axios from 'axios'
import VueAxios from 'vue-axios'



Vue.use(VueRouter ,VueAxios , axios)
Vue.component('pagination', require('laravel-vue-pagination'));

const router = new VueRouter({
  routes,
  mode:"history"
   // short for `routes: routes`
})


const app = new Vue({
    el: '#app',
    router
});
