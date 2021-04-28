import { ChangeDetectionStrategy, Component, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-header-menu',
    templateUrl: './header-menu.component.html',
    styleUrls: ['./header-menu.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: { class: 'mp-header-menu' },
})
export class HeaderMenuComponent {}
