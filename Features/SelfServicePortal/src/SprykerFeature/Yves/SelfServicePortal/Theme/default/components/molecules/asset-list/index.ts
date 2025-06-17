import './style.scss';
import register from 'ShopUi/app/registry';
export default register('asset-list', () => import(/* webpackMode: "eager" */ './asset-list'));
