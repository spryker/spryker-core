import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { UserMenuModule } from '@spryker/user-menu';
import { HeaderMenuComponent } from './header-menu.component';

@NgModule({
    imports: [CommonModule, UserMenuModule],
    declarations: [HeaderMenuComponent],
    exports: [HeaderMenuComponent],
})
export class HeaderMenuModule {}
