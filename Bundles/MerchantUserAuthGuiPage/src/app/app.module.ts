import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { ButtonModule, ButtonComponent } from '@spryker/button';
import { CustomElementModule } from '@spryker/web-components';

import { AppComponent } from './app.component';
import { LoginComponent } from './login/login.component';

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent
  ],
  imports: [
    BrowserModule,
    ButtonModule
  ],
  providers: [],
})
export class AppModule extends CustomElementModule {
  protected components = [{selector: 'spy-button', component: () => Promise.resolve(ButtonComponent)}, {selector: 'mp-login', component: () => Promise.resolve(LoginComponent)}];
}
