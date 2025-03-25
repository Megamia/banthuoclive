import { createRouter, createWebHistory } from "vue-router";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: () => import("../views/HomePage.vue"),
      meta: { layout: "default" },
    },
    {
      path: "/login",
      name: "login",
      component: () => import("../views/LoginSignupPage.vue"),
      meta: { layout: "default" },
    },
    {
      path: "/product/:slug",
      name: "productdetail",
      component: () => import("../views/ProductDetail.vue"),
      meta: { layout: "default" },
    },
    {
      path: "/category/:slug1/:slug2?",
      name: "category",
      component: () => import("../views/CategoryPage.vue"),
      meta: { layout: "default" },
    },
    {
      path: "/test",
      name: "test",
      component: () => import("../views/TestPage.vue"),
    },
    {
      path: "/chatBot",
      name: "chatBot",
      component: () => import("../components/chatBot/ChatBotComponent.vue"),
    },
    {
      path: "/nav",
      name: "nav",
      component: () => import("../components/NavProductComponent.vue"),
      meta: { layout: "default" },
    },
    {
      path: "/menu",
      name: "menu",
      component: () => import("../components/MenuComponent.vue"),
      meta: { layout: "default" },
    },
    {
      path: "/slide",
      name: "slide",
      component: () => import("../components/HomeHeroSlideComponent.vue"),
      meta: { layout: "default" },
    },
    {
      path: "/compareProducts",
      name: "compareProducts",
      component: () => import("../views/CompareProducts.vue"),
      meta: { layout: "default" },
    },
    {
      path: "/payment",
      name: "payment",
      component: () => import("../views/PaymentPage.vue"),
      meta: { layout: "default" },
    },
    {
      path: "/cart",
      name: "cart",
      component: () => import("../views/CartPage.vue"),
      meta: { layout: "default" },
    },
    {
      path: "/guaranteeHome",
      name: "guaranteeHome",
      component: () => import("../views/GuaranteeHomePage.vue"),
      meta: { layout: "guarantee" },
    },
    {
      path: "/news",
      name: "news",
      component: () => import("../views/newsPage/NewsPage.vue"),
      meta: { layout: "default" },
    },
    {
      path: "/detailNews/:slug",
      name: "detailNews",
      component: () => import("../views/newsPage/detail/ItemNewDetail.vue"),
      meta: { layout: "default" },
    },
    {
      path: "/itemNavNewComponent",
      name: "itemNavNewComponent",
      component: () =>
        import("../components/news/latestNews/ItemLatestNewsComponent.vue"),
      meta: { layout: "default" },
    },
    {
      path: "/about",
      name: "about",
      component: () => import("../views/aboutPage/AboutPage.vue"),
      meta: { layout: "default" },
    },
    {
      path: "/payment/order-received/:slug",
      name: "orderdetail_haveslug",
      component: () => import("../views/OrderDetailPage.vue"),
      meta: { layout: "default" },
    },
    {
      path: "/payment/order-received",
      name: "orderdetail_noslug",
      component: () => import("../views/OrderDetailPage.vue"),
      meta: { layout: "default" },
    },
    {
      path: "/profile",
      name: "profile",
      component: () => import("../views/ProfilePage.vue"),
      meta: { layout: "default" },
    },
  ],
  scrollBehavior(to, from, savedPosition) {
    return { top: 0, behavior: "smooth" };
  },
});

export default router;
