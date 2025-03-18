import { createStore } from "vuex";
import storeProducts from "./StoreProducts/storeProducts";
import createPersistedState from "vuex-persistedstate";
import CryptoJS from "crypto-js";

const SECRET_KEY = "your-secret-key";

const encryptData = (data) => {
  try {
    return CryptoJS.AES.encrypt(JSON.stringify(data), SECRET_KEY).toString();
  } catch (error) {
    console.error("Encryption error:", error);
    return null;
  }
};

const decryptData = (cipherText) => {
  try {
    const bytes = CryptoJS.AES.decrypt(cipherText, SECRET_KEY);
    return JSON.parse(bytes.toString(CryptoJS.enc.Utf8));
  } catch (error) {
    console.error("Decryption error:", error);
    return null;
  }
};

const store = createStore({
  modules: {
    product: storeProducts,
  },
  plugins: [
    createPersistedState({
      key: "vuex",
      paths: ["product.dataStoreProducts", "product.dataStoreCart"],
      storage: {
        getItem: (key) => {
          try {
            const data = localStorage.getItem(key);
            return data ? decryptData(data) : null;
          } catch (error) {
            console.error(
              `Lỗi khi lấy dữ liệu từ localStorage (${key}):`,
              error
            );
            return null;
          }
        },
        setItem: (key, value) => {
          try {
            const encryptedData = encryptData(value);
            localStorage.setItem(key, encryptedData);
          } catch (error) {
            console.error(
              `Lỗi khi lưu dữ liệu vào localStorage (${key}):`,
              error
            );
          }
        },
        removeItem: (key) => {
          try {
            localStorage.removeItem(key);
          } catch (error) {
            console.error(
              `Lỗi khi xóa dữ liệu từ localStorage (${key}):`,
              error
            );
          }
        },
      },

      // storage: {
      //   getItem: (key) => {
      //     const data = localStorage.getItem(key);
      //     return data ? JSON.parse(data) : null;
      //   },
      //   setItem: (key, value) => {
      //     localStorage.setItem(key, JSON.stringify(value));
      //   },
      //   removeItem: (key) => localStorage.removeItem(key),
      // },
    }),
  ],
});

export default store;
