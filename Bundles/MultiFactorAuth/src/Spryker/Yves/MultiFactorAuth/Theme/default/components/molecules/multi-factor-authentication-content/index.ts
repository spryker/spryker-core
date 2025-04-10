import register from 'ShopUi/app/registry';
import './style.scss';
export default register(
    'multi-factor-authentication-content',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "multi-factor-authentication-content" */
            './multi-factor-authentication-content'
        ),
);
