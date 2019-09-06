import HomeComponent from './components/Admin/HomeComponent'
import Form from './components/Admin/Form'
//let HomeComponent=require('./components/Admin/HomeComponent').default;

export const routes = [
  	{ 
	  	path: '/', 
	  	component: HomeComponent,
	  	name:"/" 
  	},
  	{ 
	  	path: '/form', 
	  	component: Form,
	  	name:"/form" 
  	}
]