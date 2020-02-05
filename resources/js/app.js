
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
// require('./datetimepicker/main');
require('./select2/main');
require('./charjs/Chart.bundle')

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
	el: '#app',
	data: () => {
		return {
			consulta: '',
			cargando: false,
			mesDesde: '01',
			yearDesde: '2007',
			mesHasta: '12',
			yearHasta: '2007',
			relatorios: [],
			barData: {},
			pieData: {}
		}
	},
	methods: {
		getRelatorio(){
			if($('[name="usuarios[]"]').val().length){
				if(parseInt(this.yearDesde) <= parseInt(this.yearHasta)){
					if(parseInt(this.mesDesde) <= parseInt(this.mesHasta)){
						this.consulta = 'relatorio'
						this.cargando = true

						let data = {
							params: {
								mesDesde: this.mesDesde,
								yearDesde: this.yearDesde,
								mesHasta: this.mesHasta,
								yearHasta: this.yearHasta,
								usuarios: $('[name="usuarios[]"]').val()
							}
						}

						axios.get('/relatorio', data)
							 .then((res) => {
								this.relatorios = res.data
								this.cargando = false
							 })
							 .catch((error) => {
								console.log(error)
								this.cargando = false
							 })
					} else {
						alert('Por favor seleccione un rango de fecha correcto.')
					}
				} else {
					alert('Por favor seleccione un rango de fecha correcto.')
				}
			} else{
				alert('Seleccione al menos un consultor')
			}
		},
		getDataBar(){
			if($('[name="usuarios[]"]').val().length){
				if(parseInt(this.yearDesde) <= parseInt(this.yearHasta)){
					if(parseInt(this.mesDesde) <= parseInt(this.mesHasta)){
						this.consulta = 'bar'
						this.cargando = true

						let data = {
							params: {
								mesDesde: this.mesDesde,
								yearDesde: this.yearDesde,
								mesHasta: this.mesHasta,
								yearHasta: this.yearHasta,
								usuarios: $('[name="usuarios[]"]').val()
							}
						}

						axios.get('/bar', data)
							 .then((res) => {
								let ctx = this.$refs.chartBar.getContext('2d')
								this.barData = res.data
								this.cargando = false

								if(typeof window.myBar !== 'undefined'){
									window.myBar.destroy()
								}
								window.myBar = new Chart(ctx, {
									type: 'bar',
									data: this.barData,
									options: {
										responsive: true,
										legend: {
											position: 'top',
										},
										title: {
											display: true,
											text: 'Performance Comerc...'
										},
										tooltips: {
											callbacks: {
												label: function(tooltipItem, data) {
													return 'R$ ' + Number(parseFloat(tooltipItem.yLabel).toFixed(2)).toLocaleString('es', {
														minimumFractionDigits: 2
													});
												}
											}
										},
										scales: {
											yAxes: [{
												ticks: {
													callback: function(value, index, values) {
														return 'R$ ' + Number(parseFloat(value).toFixed(2)).toLocaleString('es', {
															minimumFractionDigits: 2
														})
													}
												}
											}]
										}
									}
								});
							 })
							 .catch((error) => {
								console.log(error)
								this.cargando = false
							 })
					} else {
						alert('Por favor seleccione un rango de fecha correcto.')
					}
				} else {
					alert('Por favor seleccione un rango de fecha correcto.')
				}
			} else{
				alert('Seleccione al menos un consultor')
			}
		},
		getDataPie(){
			if($('[name="usuarios[]"]').val().length){
				if(parseInt(this.yearDesde) <= parseInt(this.yearHasta)){
					if(parseInt(this.mesDesde) <= parseInt(this.mesHasta)){
						this.consulta = 'pie'
						this.cargando = true

						let data = {
							params: {
								mesDesde: this.mesDesde,
								yearDesde: this.yearDesde,
								mesHasta: this.mesHasta,
								yearHasta: this.yearHasta,
								usuarios: $('[name="usuarios[]"]').val()
							}
						}

						axios.get('/pie', data)
							 .then((res) => {
								let ctx = this.$refs.chartPie.getContext('2d')
								this.pieData = res.data
								this.cargando = false
								this.pieData.options = {
									tooltips: {
										responsive: true,
										callbacks: {
											label: function(tooltipItem, data) {
												console.log(data)
												return data.labels[tooltipItem.index] + ': ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] + '%'
											}
										}
									}
								}
								
								if(typeof window.myPie !== 'undefined'){
									window.myPie.destroy()
								}
								window.myPie = new Chart(ctx, this.pieData)
							 })
							 .catch((error) => {
								console.log(error)
								this.cargando = false
							 })
					} else {
						alert('Por favor seleccione un rango de fecha correcto.')
					}
				} else {
					alert('Por favor seleccione un rango de fecha correcto.')
				}
			} else{
				alert('Seleccione al menos un consultor')
			}
		}
	}
});
