import register from 'ShopUi/app/registry';
export default register(
    'service-point-attributes-transfer',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "service-point-attributes-transfer" */
            './service-point-attributes-transfer'
        ),
);
