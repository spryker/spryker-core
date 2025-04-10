import register from 'ShopUi/app/registry';
export default register(
    'multi-factor-authentication-handler',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "multi-factor-authentication-handler" */
            './multi-factor-authentication-handler'
        ),
);
