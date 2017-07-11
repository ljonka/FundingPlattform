
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('../../../node_modules/chart.js/dist/Chart.bundle');
require('./utils');
window.NameGenerator = require('../../../node_modules/nodejs-randomnames/NameGenerator');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));
/*
const app = new Vue({
    el: '#app'
});
*/

Echo.channel('supporter.updated')
    .listen('SupporterUpdated', (e) => {
      /*
        console.log(e.supporter.uuid);
        console.log(e.supporter.vorname);
        console.log(e.supporter.nachname);
        console.log(e.supporter.beitrag);
        */
        var updatePosition = -1;
        for (var k in window.myLine.data.labels){
            if (window.myLine.data.labels[k] === e.supporter.vorname) {
                 updatePosition = k;
            }
        }
        if(updatePosition >= 0){
          window.myLine.data.datasets[0].data.splice(updatePosition, 1, e.supporter.beitrag);
          window.myLine.data.datasets[1].data.splice(updatePosition, 1, e.supporter.beitrag - (e.supporter.beitrag*0.3));
        }else{
          window.myLine.data.labels.push(e.supporter.vorname);
          window.myLine.data.datasets[0].data.push(e.supporter.beitrag);
          window.myLine.data.datasets[1].data.push(e.supporter.beitrag - (e.supporter.beitrag*0.3));
          window.myLine.data.datasets[2].data.push(e.calculation.singlesupports);
          window.myLine.data.datasets[3].data.push(e.calculation.funded);
        }
        window.myLine.data.datasets[2].data.forEach(function(element, index, array){
          window.myLine.data.datasets[2].data.splice(index, 1, e.calculation.singlesupports);
          window.myLine.data.datasets[3].data.splice(index, 1, e.calculation.funded);
        });
        window.myLine.update();
    });
