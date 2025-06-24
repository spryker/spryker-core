import './style.scss';
import register from 'ShopUi/app/registry';
export default register(
    'google-map',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "google-map" */
            './google-map'
        ),
);
