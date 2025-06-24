import './style.scss';
import register from 'ShopUi/app/registry';
export default register(
    'ssp-service-point-finder',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "ssp-service-point-finder" */
            './ssp-service-point-finder'
        ),
);
