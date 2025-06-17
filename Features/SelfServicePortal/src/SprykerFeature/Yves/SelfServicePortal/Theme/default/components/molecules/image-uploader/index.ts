import './style.scss';
import register from 'ShopUi/app/registry';
export default register(
    'image-uploader',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "image-uploader" */
            './image-uploader'
        ),
);
