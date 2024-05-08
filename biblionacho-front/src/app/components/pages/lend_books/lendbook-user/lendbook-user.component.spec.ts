import { ComponentFixture, TestBed } from '@angular/core/testing';

import { LendbookUserComponent } from './lendbook-user.component';

describe('LendbookUserComponent', () => {
  let component: LendbookUserComponent;
  let fixture: ComponentFixture<LendbookUserComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [LendbookUserComponent]
    });
    fixture = TestBed.createComponent(LendbookUserComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
