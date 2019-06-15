
 

require('./bootstrap');

window.Vue = require('vue');
 

 
Vue.component('leagues-table', require('./components/LeaguesTable.vue').default);
 

const app = new Vue({
    el: '#app'
});
