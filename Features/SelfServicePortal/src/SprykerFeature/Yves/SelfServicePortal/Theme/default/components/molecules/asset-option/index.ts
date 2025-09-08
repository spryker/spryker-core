import './style.scss';
import register from 'ShopUi/app/registry';
export default register('asset-option', () => import(/* webpackMode: "eager" */ './asset-option'));
