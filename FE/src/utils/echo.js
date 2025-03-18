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

// echo.connector.pusher.connection.bind("connected", () => {
//   console.log("WebSocket Connected!");
// });

// echo.connector.pusher.connection.bind("disconnected", () => {
//   console.log("WebSocket Disconnected!");
// });

// echo.connector.pusher.connection.bind("reconnected", () => {
//   console.log(" WebSocket Reconnected!");
// });

// echo.connector.pusher.connection.bind("error", (error) => {
//   console.error("WebSocket Connection Error:", error);
// });

export default echo;
