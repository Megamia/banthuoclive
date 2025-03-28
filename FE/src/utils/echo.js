import Echo from "laravel-echo";
import Pusher from "pusher-js";

const echo = new Echo({
  broadcaster: "pusher",
  key: "39fb1bb26c9026f8e279",
  cluster: "mt1",
  wsPort: 6001,
  forceTLS: false,
  disableStats: true,
});
export default echo;
