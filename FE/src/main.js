import "./assets/main.css";
import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import Antd from "ant-design-vue";
import store from "./store/store";
import "./style.css";
import global from 'global';

const app = createApp(App);
store.dispatch("product/startAutoClear");
window.global = global;
app.use(router);
app.use(Antd);
app.use(store);
app.mount("#app");
