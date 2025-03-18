import { openDB } from "idb";

const dbPromise = openDB("myDatabase", 2, {
  upgrade(db) {
    const stores = ["products", "detailProduct", "cart", "compare", "category"];
    stores.forEach((store) => {
      if (!db.objectStoreNames.contains(store)) {
        db.createObjectStore(store, { keyPath: "id" });
      }
    });
  },
});

export const getAllDataFromIndexedDB = async () => {
  try {
    const db = await dbPromise;
    const stores = ["products", "detailProduct", "cart", "compare", "category"];
    let allData = {};

    for (const storeName of stores) {
      allData[storeName] = await db.getAll(storeName);
    }

    return allData;
  } catch (error) {
    console.error("❌ Lỗi khi lấy toàn bộ dữ liệu từ IndexedDB:", error);
    return null;
  }
};

export const saveDataToIndexedDB = async (storeName, data) => {
  try {
    const db = await dbPromise;
    const tx = db.transaction(storeName, "readwrite");
    const store = tx.objectStore(storeName);
    const timestamp = Date.now();

    if (!Array.isArray(data)) {
      console.error(`❌ Dữ liệu không hợp lệ cho ${storeName}:`, data);
      return;
    }

    await store.clear();

    for (const item of data) {
      item.timestamp = timestamp;
      store.put(item);
    }

    await tx.done;
  } catch (error) {
    console.error("❌ Lỗi khi lưu dữ liệu vào IndexedDB:", error);
  }
};

export const getDataFromIndexedDB = async (storeName) => {
  try {
    const db = await dbPromise;
    const tx = db.transaction(storeName, "readonly");
    const store = tx.objectStore(storeName);
    const data = await store.getAll();
    await tx.done;
    return data;
  } catch (error) {
    console.error(`❌ Lỗi khi lấy dữ liệu từ ${storeName}:`, error);
    return [];
  }
};

export const deleteItemFromIndexedDB = async (storeName, itemId) => {
  try {
    const db = await dbPromise;
    const tx = db.transaction(storeName, "readwrite");
    await tx.objectStore(storeName).delete(itemId);
    await tx.done;
  } catch (error) {
    console.error(
      `❌ Lỗi khi xóa item có ID ${itemId} trong ${storeName}:`,
      error
    );
  }
};

export const clearAllDataFromIndexedDB = async () => {
  try {
    const db = await dbPromise;
    const stores = ["products", "detailProduct", "cart", "compare", "category"];
    const tx = db.transaction(stores, "readwrite");

    await Promise.all(stores.map((store) => tx.objectStore(store).clear()));

    await tx.done;
  } catch (error) {
    console.error("❌ Lỗi khi xóa toàn bộ dữ liệu IndexedDB:", error);
  }
};

export const updateItemInIndexedDB = async (item) => {
  try {
    const db = await dbPromise;
    const tx = db.transaction("cart", "readwrite");
    const store = tx.objectStore("cart");

    const existingItem = await store.get(item.id);

    if (existingItem) {
      existingItem.quantity = item.quantity || existingItem.quantity;
      existingItem.timestamp = Date.now();
      await store.put(existingItem);
    } else {
      item.timestamp = Date.now();
      await store.put(item);
    }

    await tx.done;
  } catch (error) {
    console.error(" Lỗi khi cập nhật phần tử trong giỏ hàng:", error);
  }
};
