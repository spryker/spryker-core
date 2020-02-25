import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ZedLayoutMainComponent } from './zed-layout-main.component';

describe('ZedLayoutMainComponent', () => {
  let component: ZedLayoutMainComponent;
  let fixture: ComponentFixture<ZedLayoutMainComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ZedLayoutMainComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ZedLayoutMainComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
