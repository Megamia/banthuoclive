let autoClearTimeout = null;

const storeProducts = {
  namespaced: true,
  state: {
    dataStoreProducts: [],
    dataStoreCart: [],
    autoClearTimeout: null,
  },
  mutations: {
    // Product
    setDataStoreProducts(state, payload) {
      state.dataStoreProducts = payload.dataStoreProducts;
    },
    clearDataStoreProducts(state) {
      state.dataStoreProducts = [];
    },
    removeItemProduct(state, itemId) {
      state.dataStoreProducts = state.dataStoreProducts.filter(
        (item) => item.id !== itemId
      );
    },
    // Product

    // Cart
    setDataStoreCart(state, payload) {
      state.dataStoreCart = payload.dataStoreCart;
    },
    clearDataStoreCart(state) {
      state.dataStoreCart = [];
    },
    removeItemCart(state, itemId) {
      state.dataStoreCart = state.dataStoreCart.filter(
        (item) => item.id !== itemId
      );
    },
    updateItemCart(state, updatedItem) {
      const index = state.dataStoreCart.findIndex(
        (item) => item.id === updatedItem.id
      );
      if (index !== -1) {
        state.dataStoreCart[index] = updatedItem;
      }
    },
    // Cart

    setAutoClearTimeout(state, timeout) {
      state.autoClearTimeout = timeout;
    },
    clearAutoClearTimeout(state) {
      if (state.autoClearTimeout) {
        clearTimeout(state.autoClearTimeout);
        state.autoClearTimeout = null;
      }
    },
  },
  actions: {
    // Product
    clearDataStoreProducts({ commit }) {
      commit("clearDataStoreProducts");
    },

    deleteItemProduct({ commit }, itemId) {
      commit("removeItemProduct", itemId);
    },
    // Product

    // Cart
    clearDataStoreCart({ commit }) {
      commit("clearDataStoreCart");
      localStorage.removeItem("vuex");
      localStorage.removeItem("autoClearTime"); // Xóa thời gian lưu trữ
      return Promise.resolve();
    },

    deleteItemCart({ commit }, itemId) {
      commit("removeItemCart", itemId);
    },
    updateItemCart({ commit }, updatedItem) {
      commit("updateItemCart", updatedItem);
    },
    // Cart

    startAutoClear({ dispatch, commit }) {
      if (autoClearTimeout) {
        clearTimeout(autoClearTimeout);
      }

      const clearTime = Date.now() + 3600000; // 1h
      localStorage.setItem("autoClearTime", clearTime);

      autoClearTimeout = setTimeout(() => {
        dispatch("clearDataStoreCart");
        dispatch("startAutoClear");
      }, 3600000);

      commit("setAutoClearTimeout", autoClearTimeout);
    },

    stopAutoClear({ commit, state }) {
      if (state.autoClearTimeout) {
        clearTimeout(state.autoClearTimeout);
        commit("clearAutoClearTimeout");
      }
    },

    initializeAutoClear({ dispatch }) {
      const clearTime = localStorage.getItem("autoClearTime");
      if (clearTime) {
        const remainingTime = clearTime - Date.now();
        if (remainingTime > 0) {
          autoClearTimeout = setTimeout(() => {
            console.log("Clearing cart...");
            dispatch("clearDataStoreCart");
            dispatch("startAutoClear");
          }, remainingTime);
        } else {
          dispatch("clearDataStoreCart"); // Nếu đã hết thời gian, xóa ngay giỏ hàng
        }
      }
    },
  },
  getters: {
    getDataStoreProducts: (state) => state.dataStoreProducts,
    getDataStoreCart: (state) => state.dataStoreCart,
  },
};

export default storeProducts;
