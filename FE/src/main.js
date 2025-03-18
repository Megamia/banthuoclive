import "./assets/main.css";
import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import Antd from "ant-design-vue";
import store from "./store/store";
import "./style.css";

const app = createApp(App);
store.dispatch("product/startAutoClear");

app.use(router);
app.use(Antd);
app.use(store);
app.mount("#app");
