import register from 'ShopUi/app/registry';
export default register(
    'service-point-shipment-types',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "service-point-shipment-types" */
            './service-point-shipment-types'
        ),
);
