import { createRouter, createWebHistory } from "vue-router";
import invoiceIndex from "../components/invoices/index.vue";
import notFound from "../components/NotFound.vue";
import newInvoiceVue from "../components/invoices/newInvoice.vue";
import singleInvoiceVue from "../components/invoices/SingleInvoice.vue";

const routes = [
    {
        path: "/",
        component: invoiceIndex,
    },
    {
        path: "/invoice/new",
        component: newInvoiceVue,
    },
    {
        path: "/invoice/show/:id",
        component: singleInvoiceVue,
        props: true,
    },
    {
        path: "/:pathMatch(.*)*",
        component: notFound,
    },
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
