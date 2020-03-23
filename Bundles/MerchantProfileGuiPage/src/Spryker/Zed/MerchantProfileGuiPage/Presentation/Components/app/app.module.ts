import { BrowserModule } from '@angular/platform-browser';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { NgModule } from '@angular/core';
import { CustomElementModule } from '@spryker/web-components';
import { ButtonModule, ButtonComponent } from '@spryker/button';
import { FormItemModule, FormItemComponent } from '@spryker/form-item';
import { CardModule, CardComponent } from '@spryker/card';
import { InputModule, InputComponent } from '@spryker/input';
import { CollapsibleModule, CollapsibleComponent } from '@spryker/collapsible';
import { IconModule } from '@spryker/icon';
import { TextareaModule, TextareaComponent } from '@spryker/textarea';
import { ToggleModule, ToggleComponent } from '@spryker/toggle';
import { SelectModule, SelectComponent } from '@spryker/select';
import { TabsModule, TabsComponent, TabComponent } from '@spryker/tabs';
import { LabelModule, LabelComponent } from '@spryker/label';

import { ProfileComponent } from './profile/profile.component';
import { ProfileModule } from './profile/profile.module';
import { IconUnitedStatesModule, IconGermanyModule } from '../icons';

@NgModule({
	imports: [
		BrowserAnimationsModule,
		BrowserModule,
		ButtonModule,
		CardModule,
		FormItemModule,
		InputModule,
		CollapsibleModule,
		IconModule,
		TextareaModule,
		ToggleModule,
		SelectModule,
		LabelModule,
		IconUnitedStatesModule,
		IconGermanyModule,
		TabsModule,
		ProfileModule,
	],
	providers: [],
	declarations: [],
})
export class AppModule extends CustomElementModule {
	protected components = [
		{
			selector: 'mp-profile',
			component: ProfileComponent
		},
		{
			selector: 'spy-button',
			component: ButtonComponent
		},
		{
			selector: 'spy-form-item',
			component: FormItemComponent
		},
		{
			selector: 'spy-input',
			component: InputComponent
		},
		{
			selector: 'spy-textarea',
			component: TextareaComponent
		},
		{
			selector: 'spy-card',
			component: CardComponent
		},
		{
			selector: 'spy-collapsible',
			component: CollapsibleComponent
		},
		{
			selector: 'spy-toggle',
			component: ToggleComponent
		},
		{
			selector: 'spy-select',
			component: SelectComponent
		},
		{
			selector: 'spy-label',
			component: LabelComponent
		},
		{
			selector: 'spy-tabs',
			component: TabsComponent
		},
		{
			selector: 'spy-tab',
			component: TabComponent
		}
	];
}
