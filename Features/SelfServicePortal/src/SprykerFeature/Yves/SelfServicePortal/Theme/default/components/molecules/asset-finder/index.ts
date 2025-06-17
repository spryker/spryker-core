import './style.scss';
import register from 'ShopUi/app/registry';
export default register('asset-finder', () => import(/* webpackMode: "eager" */ './asset-finder'));
