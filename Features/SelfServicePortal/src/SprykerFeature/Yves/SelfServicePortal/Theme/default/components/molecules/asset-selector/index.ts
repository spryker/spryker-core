import './style.scss';
import register from 'ShopUi/app/registry';
export default register('asset-selector', () => import(/* webpackMode: "eager" */ './asset-selector'));
